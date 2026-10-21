<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Tugas;
use App\Models\User;
use App\Notifications\NewTaskAssigned;
use App\Notifications\TaskApproved;
use App\Notifications\TaskRejected;
use App\Notifications\TaskSubmitted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TugasService
{
    /**
     * Create a new task.
     */
    public function createTugas(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Convert datetime-local format to MySQL datetime format
            $tanggal_mulai = str_replace('T', ' ', $data['tanggal_mulai']).':00';
            $tanggal_selesai = str_replace('T', ' ', $data['tanggal_selesai']).':00';

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
            ActivityLog::log('created', $tugas, 'Membuat tugas baru untuk '.($tugas->user?->nama ?? 'N/A'));

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
                $data['tanggal_mulai'] = str_replace('T', ' ', $data['tanggal_mulai']).':00';
            }
            if (isset($data['tanggal_selesai'])) {
                $data['tanggal_selesai'] = str_replace('T', ' ', $data['tanggal_selesai']).':00';
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
            ActivityLog::log('trashed', $tugas, 'Memindahkan tugas ke trash: '.$tugas->tugas, [
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
            ActivityLog::log('restored', $tugas, 'Mengembalikan tugas dari trash: '.$tugas->tugas);

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
            ActivityLog::log('deleted', $tugas, 'Menghapus permanen tugas: '.$tugas->tugas, [
                'tugas_data' => $tugas->toArray(),
            ]);

            // Force delete (permanent)
            return $tugas->forceDelete();
        });
    }

    /**
     * Approve a task.
     * Returns false when the task is no longer waiting for approval (race guard).
     */
    public function approveTugas(Tugas $tugas): bool
    {
        return DB::transaction(function () use ($tugas) {
            $locked = Tugas::whereKey($tugas->getKey())->lockForUpdate()->first();

            if (! $locked || $locked->status !== Tugas::STATUS_SUBMITTED) {
                return false;
            }

            $locked->status = Tugas::STATUS_APPROVED;
            $locked->save();

            // Log activity
            ActivityLog::log('approved', $locked, 'Menyetujui tugas dari '.($locked->user?->nama ?? 'N/A'));

            // Send notification to user
            $locked->load(['user', 'creator']);
            if ($locked->user) {
                $locked->user->notify(new TaskApproved($locked));
            }

            return true;
        });
    }

    /**
     * Reject a task.
     * Returns false when the task is no longer waiting for approval (race guard).
     */
    public function rejectTugas(Tugas $tugas, string $catatan)
    {
        return DB::transaction(function () use ($tugas, $catatan) {
            $locked = Tugas::whereKey($tugas->getKey())->lockForUpdate()->first();

            if (! $locked || $locked->status !== Tugas::STATUS_SUBMITTED) {
                return false;
            }

            $locked->status = Tugas::STATUS_REJECTED;
            $locked->catatan = $catatan;
            $locked->save();

            // Log activity
            ActivityLog::log('rejected', $locked, 'Menolak tugas dari '.($locked->user?->nama ?? 'N/A'), [
                'catatan' => $catatan,
            ]);

            // Send notification to user with rejection reason
            $locked->load(['user', 'creator']);
            if ($locked->user) {
                $locked->user->notify(new TaskRejected($locked));
            }

            return $locked;
        });
    }

    /**
     * Upload result file (for employee).
     * Returns false when the task no longer accepts uploads (race guard).
     */
    public function uploadResult(Tugas $tugas, $file)
    {
        return DB::transaction(function () use ($tugas, $file) {
            $locked = Tugas::whereKey($tugas->getKey())->lockForUpdate()->first();

            if (! $locked || ! in_array($locked->status, [Tugas::STATUS_PENDING, Tugas::STATUS_REJECTED], true)) {
                return false;
            }

            $oldFile = $locked->karyawan_file;
            $path = $file->store('tugas_karyawan', 'public');

            $locked->karyawan_file = $path;
            $locked->status = Tugas::STATUS_SUBMITTED;
            $locked->save();

            // Remove the previous submission file so rejected re-uploads do not pile up on disk.
            if ($oldFile) {
                Storage::disk('public')->delete($oldFile);
            }

            // Send notification to admin when task is submitted
            $locked->load(['user', 'creator']);
            if ($locked->creator) {
                $locked->creator->notify(new TaskSubmitted($locked));
            }

            ActivityLog::log('submitted', $locked, 'Karyawan submit tugas: '.$locked->tugas);

            return $locked;
        });
    }
}
