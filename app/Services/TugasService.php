<?php

namespace App\Services;

use App\Models\Tugas;
use App\Models\User;
use App\Models\ActivityLog;
use App\Notifications\NewTaskAssigned;
use App\Notifications\TaskSubmitted;
use App\Notifications\TaskApproved;
use App\Notifications\TaskRejected;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TugasService
{
    /**
     * Create a new task.
     */
    public function createTugas(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Convert datetime-local format to MySQL datetime format
            $tanggal_mulai = str_replace('T', ' ', $data['tanggal_mulai']) . ':00';
            $tanggal_selesai = str_replace('T', ' ', $data['tanggal_selesai']) . ':00';

            $tugas = Tugas::create([
                'user_id' => $data['user_id'],
                'created_by' => auth()->id(),
                'tugas' => $data['tugas'],
                'tanggal_mulai' => $tanggal_mulai,
                'tanggal_selesai' => $tanggal_selesai,
                'status' => Tugas::STATUS_PENDING,
                'priority' => $data['priority'] ?? 'medium',
            ]);

            if (isset($data['file']) && $data['file']) {
                $path = $data['file']->store('tugas_files', 'public');
                $tugas->tugas_file = $path;
                $tugas->save();
            }

            // Send notification to assigned user
            $tugas->load(['user', 'creator']);
            if ($tugas->user) {
                $tugas->user->notify(new NewTaskAssigned($tugas));
            }

            // Log activity
            ActivityLog::log('created', $tugas, 'Membuat tugas baru untuk ' . ($tugas->user?->nama ?? 'N/A'));

            return $tugas;
        });
    }

    /**
     * Update an existing task.
     */
    public function updateTugas(Tugas $tugas, array $data)
    {
        return DB::transaction(function () use ($tugas, $data) {
            $oldData = $tugas->getOriginal();

            // Convert datetime-local format if present
            if (isset($data['tanggal_mulai'])) {
                $data['tanggal_mulai'] = str_replace('T', ' ', $data['tanggal_mulai']) . ':00';
            }
            if (isset($data['tanggal_selesai'])) {
                $data['tanggal_selesai'] = str_replace('T', ' ', $data['tanggal_selesai']) . ':00';
            }

            $tugas->update($data);

            // Log activity
            ActivityLog::log('updated', $tugas, 'Memperbarui tugas', [
                'old' => $oldData,
                'new' => $tugas->getAttributes(),
            ]);

            return $tugas;
        });
    }

    /**
     * Soft delete a task (move to trash).
     */
    public function deleteTugas(Tugas $tugas)
    {
        return DB::transaction(function () use ($tugas) {
            // Log activity before soft deletion
            ActivityLog::log('trashed', $tugas, 'Memindahkan tugas ke trash: ' . $tugas->tugas, [
                'tugas_data' => $tugas->toArray(),
            ]);

            // Soft delete (move to trash)
            return $tugas->delete();
        });
    }

    /**
     * Restore a soft-deleted task from trash.
     */
    public function restoreTugas(Tugas $tugas)
    {
        return DB::transaction(function () use ($tugas) {
            $tugas->restore();

            // Log activity
            ActivityLog::log('restored', $tugas, 'Mengembalikan tugas dari trash: ' . $tugas->tugas);

            return $tugas;
        });
    }

    /**
     * Permanently delete a task.
     */
    public function forceDeleteTugas(Tugas $tugas)
    {
        return DB::transaction(function () use ($tugas) {
            // Delete files if they exist
            if ($tugas->tugas_file) {
                Storage::disk('public')->delete($tugas->tugas_file);
            }

            if ($tugas->karyawan_file) {
                Storage::disk('public')->delete($tugas->karyawan_file);
            }

            // Log activity before permanent deletion
            ActivityLog::log('deleted', $tugas, 'Menghapus permanen tugas: ' . $tugas->tugas, [
                'tugas_data' => $tugas->toArray(),
            ]);

            // Force delete (permanent)
            return $tugas->forceDelete();
        });
    }

    /**
     * Approve a task.
     */
    public function approveTugas(Tugas $tugas)
    {
        $tugas->status = Tugas::STATUS_APPROVED;
        $tugas->save();

        // Log activity
        ActivityLog::log('approved', $tugas, 'Menyetujui tugas dari ' . $tugas->user->nama);

        // Send notification to user
        $tugas->load(['user', 'creator']);
        if ($tugas->user) {
            $tugas->user->notify(new TaskApproved($tugas));
        }

        return $tugas;
    }

    /**
     * Reject a task.
     */
    public function rejectTugas(Tugas $tugas, string $catatan)
    {
        $tugas->status = Tugas::STATUS_REJECTED;
        $tugas->catatan = $catatan;
        $tugas->save();

        // Log activity
        ActivityLog::log('rejected', $tugas, 'Menolak tugas dari ' . $tugas->user->nama, [
            'catatan' => $catatan,
        ]);

        // Send notification to user with rejection reason
        $tugas->load(['user', 'creator']);
        if ($tugas->user) {
            $tugas->user->notify(new TaskRejected($tugas));
        }

        return $tugas;
    }

    /**
     * Upload result file (for employee).
     */
    public function uploadResult(Tugas $tugas, $file)
    {
        $path = $file->store('tugas_karyawan', 'public');
        $tugas->karyawan_file = $path;

        $wasSubmitted = false;
        if ($tugas->status === Tugas::STATUS_PENDING || $tugas->status === Tugas::STATUS_REJECTED) {
            $tugas->status = Tugas::STATUS_SUBMITTED;
            $wasSubmitted = true;
        }

        $tugas->save();

        // Send notification to admin when task is submitted
        if ($wasSubmitted) {
            $tugas->load(['user', 'creator']);
            if ($tugas->creator) {
                $tugas->creator->notify(new TaskSubmitted($tugas));
            }

            // Log activity
            ActivityLog::log('submitted', $tugas, 'Karyawan submit tugas: ' . $tugas->tugas);
        }

        return $tugas;
    }
}
