<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Tugas;
use App\Models\User;
use App\Services\StatisticsService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TugasExport;
use Barryvdh\DomPDF\Facade\Pdf;

class TugasApiController extends Controller
{
    use AuthorizesRequests;

    protected $statsService;

    public function __construct(StatisticsService $statsService)
    {
        $this->statsService = $statsService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth('sanctum')->user();

        // Only get non-deleted records
        $query = Tugas::with('user:id,nama,email,jabatan')->whereNull('deleted_at');

        if ($user->isAdmin()) {
            $tugas = $query->whereHas('user', fn($q) => $q->where('jabatan', 'Karyawan'))
                ->latest()
                ->get();
        } else {
            $tugas = $query->where('user_id', $user->id)
                ->latest()
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $tugas,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tugas' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date_format:Y-m-d\TH:i',
            'tanggal_selesai' => 'required|date_format:Y-m-d\TH:i|after_or_equal:tanggal_mulai',
            'priority' => 'nullable|in:low,medium,high',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $user = User::findOrFail($validated['user_id']);

        if (!$user->isEmployee()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya bisa menugaskan ke karyawan',
            ], 403);
        }

        // Convert datetime-local format to MySQL datetime format
        $tanggal_mulai = str_replace('T', ' ', $validated['tanggal_mulai']) . ':00';
        $tanggal_selesai = str_replace('T', ' ', $validated['tanggal_selesai']) . ':00';

        $tugas = Tugas::create([
            'user_id' => $validated['user_id'],
            'tugas' => $validated['tugas'],
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'status' => 'pending',
            'priority' => $validated['priority'] ?? 'medium',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('tugas_files', 'public');
            $tugas->tugas_file = $path;
            $tugas->save();
        }

        // Load user relationship for response
        $tugas->load('user:id,nama,email,jabatan');

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dibuat',
            'data' => $tugas,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tugas $tugas)
    {
        $this->authorize('view', $tugas);

        $tugas->load('user:id,nama,email,jabatan');

        return response()->json([
            'success' => true,
            'data' => $tugas,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tugas $tugas)
    {
        $this->authorize('update', $tugas);

        $validated = $request->validate([
            'tugas' => 'sometimes|required|string|max:255',
            'tanggal_mulai' => 'sometimes|required|date_format:Y-m-d\TH:i',
            'tanggal_selesai' => 'sometimes|required|date_format:Y-m-d\TH:i|after_or_equal:tanggal_mulai',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        $oldData = $tugas->getOriginal();

        // Convert datetime-local format to MySQL datetime format if provided
        if (isset($validated['tanggal_mulai'])) {
            $validated['tanggal_mulai'] = str_replace('T', ' ', $validated['tanggal_mulai']) . ':00';
        }
        if (isset($validated['tanggal_selesai'])) {
            $validated['tanggal_selesai'] = str_replace('T', ' ', $validated['tanggal_selesai']) . ':00';
        }

        $tugas->update($validated);

        // Load user relationship for response
        $tugas->load('user:id,nama,email,jabatan');

        // Log activity
        ActivityLog::log('updated', $tugas, 'Memperbarui tugas', [
            'old' => $oldData,
            'new' => $tugas->getAttributes(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil diperbarui',
            'data' => $tugas,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tugas $tugas)
    {
        $this->authorize('delete', $tugas);

        // Delete files if they exist
        if ($tugas->tugas_file) {
            Storage::disk('public')->delete($tugas->tugas_file);
        }

        if ($tugas->karyawan_file) {
            Storage::disk('public')->delete($tugas->karyawan_file);
        }

        // Log activity before deletion
        ActivityLog::log('deleted', $tugas, 'Menghapus tugas: ' . $tugas->tugas, [
            'tugas_data' => $tugas->toArray(),
        ]);

        // Force delete (permanent) instead of soft delete
        $tugas->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dihapus',
        ]);
    }

    /**
     * Approve the specified task.
     */
    public function approve(Tugas $tugas)
    {
        $this->authorize('approve', $tugas);

        $tugas->status = 'approved';
        $tugas->save();

        // Log activity
        ActivityLog::log('approved', $tugas, 'Menyetujui tugas dari ' . $tugas->user->nama);

        return response()->json([
            'success' => true,
            'message' => 'Tugas disetujui',
        ]);
    }

    /**
     * Reject the specified task.
     */
    public function reject(Request $request, Tugas $tugas)
    {
        $this->authorize('reject', $tugas);

        $validated = $request->validate([
            'catatan' => 'required|string|max:255',
        ]);

        $tugas->status = 'rejected';
        $tugas->catatan = $validated['catatan'];
        $tugas->save();

        // Log activity
        $tugas->load('user');
        ActivityLog::log('rejected', $tugas, 'Menolak tugas dari ' . $tugas->user->nama, [
            'catatan' => $validated['catatan'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tugas ditolak',
            'data' => $tugas,
        ]);
    }

    /**
     * Upload file for task.
     */
    public function upload(Request $request, Tugas $tugas)
    {
        $this->authorize('upload', $tugas);

        $validated = $request->validate([
            'file' => 'required|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $path = $request->file('file')->store('tugas_karyawan', 'public');
        $tugas->karyawan_file = $path;

        if ($tugas->status === 'pending') {
            $tugas->status = 'submitted';
        }

        $tugas->save();

        return response()->json([
            'success' => true,
            'message' => 'File berhasil diupload',
            'data' => $tugas,
        ]);
    }

    /**
     * Get list of employees for task assignment.
     */
    public function karyawanList()
    {
        $karyawan = User::employees()->get(['id', 'nama', 'email']);

        return response()->json([
            'success' => true,
            'data' => $karyawan,
        ]);
    }

    /**
     * Export tasks to Excel.
     */
    public function excel()
    {
        $filename = now()->format('Ymd_His');
        return Excel::download(new TugasExport, "DataTugas_{$filename}.xlsx");
    }

    /**
     * Export tasks to PDF.
     */
    public function pdf()
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth('sanctum')->user();

            if ($user->isAdmin()) {
                // Admin PDF
                $tugas = Tugas::with('user')
                    ->whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')
                    ->get();
                $stats = $this->statsService->calculateTaskStats($tugas);

                $data = [
                    'tugas' => $tugas,
                    'stats' => $stats,
                    'tanggal' => now()->format('d-m-Y'),
                    'jam' => now()->format('H:i:s'),
                ];

                $filename = 'Laporan_Tugas_Admin_' . now()->format('Ymd_His') . '.pdf';

                $pdf = Pdf::loadView('admin.tugas.pdf', ['data' => $data])
                    ->setPaper('A4', 'landscape')
                    ->setOptions([
                        'defaultFont' => 'Arial',
                        'isRemoteEnabled' => true,
                        'isHtml5ParserEnabled' => true,
                    ]);

                return $pdf->download($filename);

            } else {
                // Employee PDF
                $tugas = Tugas::with('user')
                    ->whereNull('deleted_at')
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

                $data = [
                    'tugas' => $tugas,
                    'tanggal' => now()->format('d-m-Y'),
                    'jam' => now()->format('H:i:s'),
                ];

                $filename = 'Laporan_Tugas_Saya_' . now()->format('Ymd_His') . '.pdf';

                $pdf = Pdf::loadView('karyawan.tugas.pdf', ['data' => $data])
                    ->setPaper('A4', 'portrait')
                    ->setOptions([
                        'defaultFont' => 'Arial',
                        'isRemoteEnabled' => true,
                        'isHtml5ParserEnabled' => true,
                    ]);

                return $pdf->download($filename);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat PDF: ' . $e->getMessage()
            ], 500);
        }
    }

}
