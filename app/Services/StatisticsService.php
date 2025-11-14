<?php

namespace App\Services;

use App\Models\Tugas;
use App\Models\User;
use Illuminate\Support\Collection;

class StatisticsService
{
    /**
     * Calculate task statistics for a collection of tasks.
     */
    public function calculateTaskStats(Collection $tugas): array
    {
        $total = $tugas->count();
        $today = now();

        $approved = $tugas->where('status', 'approved')->count();
        $rejected = $tugas->where('status', 'rejected')->count();
        $pending = $tugas->where('status', 'pending')->count();
        $submitted = $tugas->where('status', 'submitted')->count();

        $overdue = $tugas->filter(function ($task) use ($today) {
            return $task->tanggal_selesai < $today && $task->status !== 'approved';
        })->count();

        return [
            'total' => $total,
            'approved' => $approved,
            'rejected' => $rejected,
            'pending' => $pending,
            'submitted' => $submitted,
            'overdue' => $overdue,
            'approved_percentage' => $total > 0 ? round(($approved / $total) * 100, 1) : 0,
            'rejected_percentage' => $total > 0 ? round(($rejected / $total) * 100, 1) : 0,
            'pending_percentage' => $total > 0 ? round(($pending / $total) * 100, 1) : 0,
            'submitted_percentage' => $total > 0 ? round(($submitted / $total) * 100, 1) : 0,
            'overdue_percentage' => $total > 0 ? round(($overdue / $total) * 100, 1) : 0,
            'updated_at' => now()->format('d F Y H:i:s'),
        ];
    }

    /**
     * Calculate user statistics for a collection of users.
     */
    public function calculateUserStats(Collection $users): array
    {
        $adminCount = $users->where('jabatan', 'Admin')->count();
        $userCount = $users->whereNotIn('jabatan', ['Admin'])->count();

        $nonAdminUsers = $users->whereNotIn('jabatan', ['Admin']);
        $tugasSelesai = $nonAdminUsers->where('is_tugas', true)->count();
        $tugasBelumSelesai = $nonAdminUsers->where('is_tugas', false)->count();

        $userDitugaskan = $users->whereNotIn('jabatan', ['Admin'])->where('is_tugas', true)->count();
        $userBelumDitugaskan = $users->whereNotIn('jabatan', ['Admin'])->where('is_tugas', false)->count();

        return [
            'total' => $users->count(),
            'admin_count' => $adminCount,
            'user_count' => $userCount,
            'tugas_selesai' => $tugasSelesai,
            'tugas_belum_selesai' => $tugasBelumSelesai,
            'persentase_selesai' => $nonAdminUsers->count() > 0
                ? round(($tugasSelesai / $nonAdminUsers->count()) * 100, 1)
                : 0,
            'user_ditugaskan' => $userDitugaskan,
            'user_belum_ditugaskan' => $userBelumDitugaskan,
            'user_persentase_selesai' => $userCount > 0
                ? round(($userDitugaskan / $userCount) * 100, 1)
                : 0,
            'jabatan_list' => $users->pluck('jabatan')->unique()->values()->toArray(),
            'updated_at' => now()->format('d F Y H:i:s')
        ];
    }

    /**
     * Get dashboard statistics based on user role.
     */
    public function getDashboardStats(?User $user = null): array
    {
        $user = $user ?? auth()->user();

        // Only count non-deleted records
        $totalTugas = Tugas::whereNull('deleted_at')->count();
        $totalUsers = User::count();
        $totalSelesai = Tugas::whereNull('deleted_at')->where('status', 'approved')->count();
        $totalPending = Tugas::whereNull('deleted_at')->where('status', 'pending')->count();

        return [
            'total_tugas' => $totalTugas,
            'total_users' => $totalUsers,
            'total_selesai' => $totalSelesai,
            'total_pending' => $totalPending
        ];
    }

    /**
     * Get recent tasks based on user role.
     */
    public function getRecentTasks(?User $user = null, int $limit = 5): Collection
    {
        $user = $user ?? auth()->user();

        if ($user->isAdmin()) {
            return Tugas::with('user')
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->take($limit)
                ->get();
        }

        return Tugas::with('user')
            ->whereNull('deleted_at')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }
}

