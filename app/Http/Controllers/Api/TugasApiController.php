<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\User;
use App\Services\StatisticsService;
use App\Services\TugasService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TugasExport;
use Barryvdh\DomPDF\Facade\Pdf;

class TugasApiController extends Controller
{
    use AuthorizesRequests;

    protected $statsService;
    protected $tugasService;

    public function __construct(StatisticsService $statsService, TugasService $tugasService)
    {
        $this->statsService = $statsService;
        $this->tugasService = $tugasService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Refresh user from database to ensure we have the latest data
        $user->refresh();

        // Only get non-deleted records
        $query = Tugas::with(['user:id,nama,email,jabatan,profile_photo_path', 'creator:id,nama,email,jabatan,profile_photo_path'])->whereNull('deleted_at');

        if ($user->isAdmin()) {
            $tugas = $query->where('created_by', $user->id)
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
            'tugas' => 'required|string|max:65535',
            'tanggal_mulai' => 'required|date_format:Y-m-d\TH:i',
            'tanggal_selesai' => 'required|date_format:Y-m-d\TH:i|after_or_equal:tanggal_mulai',
            'priority' => 'nullable|in:low,medium,high',
            'file' => 'nullable|file|max:51200', // 50MB - all formats supported
        ]);

        $user = User::findOrFail($validated['user_id']);

        if (!$user->isEmployee()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya bisa menugaskan ke karyawan',
            ], 403);
        }

        $tugas = $this->tugasService->createTugas($request->all());

        // Load user relationship for response
        $tugas->load(['user:id,nama,email,jabatan,profile_photo_path', 'creator:id,nama,email,jabatan,profile_photo_path']);

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

        $tugas->load(['user:id,nama,email,jabatan,profile_photo_path', 'creator:id,nama,email,jabatan,profile_photo_path']);

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
            'tugas' => 'sometimes|required|string|max:65535',
            'tanggal_mulai' => 'sometimes|required|date_format:Y-m-d\TH:i',
            'tanggal_selesai' => 'sometimes|required|date_format:Y-m-d\TH:i|after_or_equal:tanggal_mulai',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        $tugas = $this->tugasService->updateTugas($tugas, $validated);

        // Load user relationship for response
        $tugas->load(['user:id,nama,email,jabatan,profile_photo_path', 'creator:id,nama,email,jabatan,profile_photo_path']);

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

        $this->tugasService->deleteTugas($tugas);

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

        $this->tugasService->approveTugas($tugas);

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
            'catatan' => 'required|string|max:65535',
        ]);

        $tugas = $this->tugasService->rejectTugas($tugas, $validated['catatan']);

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
            'file' => 'required|file|max:51200', // 50MB - all formats supported
        ]);

        $tugas = $this->tugasService->uploadResult($tugas, $request->file('file'));

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
     * Get list of trashed tasks.
     */
    public function trash()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->refresh();

        $query = Tugas::onlyTrashed()
            ->with(['user:id,nama,email,jabatan,profile_photo_path', 'creator:id,nama,email,jabatan,profile_photo_path']);

        if ($user->isAdmin()) {
            $tugas = $query->where('created_by', $user->id)
                ->latest('deleted_at')
                ->get();
        } else {
            $tugas = $query->where('user_id', $user->id)
                ->latest('deleted_at')
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $tugas,
        ]);
    }

    /**
     * Restore a trashed task.
     */
    public function restore($id)
    {
        $tugas = Tugas::onlyTrashed()->findOrFail($id);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Check permissions: Admin can only restore their own tasks, Karyawan can only restore their own
        if ($user->isAdmin()) {
            if ($tugas->created_by !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk mengembalikan tugas ini',
                ], 403);
            }
        } elseif ($tugas->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengembalikan tugas ini',
            ], 403);
        }

        $this->tugasService->restoreTugas($tugas);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dikembalikan',
        ]);
    }

    /**
     * Permanently delete a task (Admin only).
     */
    public function forceDelete($id)
    {
        $tugas = Tugas::onlyTrashed()->findOrFail($id);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Only the Admin who created the task can permanently delete
        if (!$user->isAdmin() || $tugas->created_by !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus permanen tugas ini',
            ], 403);
        }

        $this->tugasService->forceDeleteTugas($tugas);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dihapus permanen',
        ]);
    }

    /**
     * Export tasks to Excel.
     */
    public function excel()
    {
        $user = auth()->user();
        $filename = now()->format('Ymd_His');
        return Excel::download(new TugasExport($user), "DataTugas_{$filename}.xlsx");
    }

    /**
     * Export tasks to PDF.
     */
    public function pdf()
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();

            if ($user->isAdmin()) {
                // Admin PDF
                $tugas = Tugas::with('user')
                    ->whereNull('deleted_at')
                    ->where('created_by', $user->id)
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
