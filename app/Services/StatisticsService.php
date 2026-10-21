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
    /**
     * Get dashboard statistics based on user role and scope.
     */
    public function getDashboardStats(?User $user = null, string $scope = 'global'): array
    {
        // Ensure we have a user
        $user = $user ?? auth()->user();

        if (!$user) {
            return [
                'total_tugas' => 0,
                'total_users' => 0,
                'total_selesai' => 0,
                'total_pending' => 0,
                'total_submitted' => 0,
                'total_rejected' => 0,
            ];
        }

        // Refresh user to ensure latest attributes are loaded
        $user = User::find($user->id);

        // Base query - Admin sees ALL tasks, Employee sees only their own
        if ($user->isAdmin()) {
            $query = Tugas::whereNull('deleted_at');

            // If scope is personal, filter by created_by
            if ($scope === 'personal') {
                $query->where('created_by', $user->id);
            }

            $totalUsers = User::count();
        } else {
            // Employee: Show only tasks assigned to them
            $query = Tugas::whereNull('deleted_at')->where('user_id', $user->id);
            $totalUsers = 0; // Employees don't see user count
        }

        // Clone query for counts to avoid modifying the base query object reference issues if any
        $totalTugas = (clone $query)->count();
        $totalSelesai = (clone $query)->where('status', 'approved')->count();
        $totalPending = (clone $query)->where('status', 'pending')->count();
        $totalSubmitted = (clone $query)->where('status', 'submitted')->count();
        $totalRejected = (clone $query)->where('status', 'rejected')->count();

        return [
            'total_tugas' => $totalTugas,
            'total_users' => $totalUsers,
            'total_selesai' => $totalSelesai,
            'total_pending' => $totalPending,
            'total_submitted' => $totalSubmitted,
            'total_rejected' => $totalRejected,
        ];
    }

    /**
     * Get recent tasks based on user role and scope.
     */
    public function getRecentTasks(?User $user = null, int $limit = 5, string $scope = 'global'): Collection
    {
        $user = $user ?? auth()->user();

        if ($user->isAdmin()) {
            $query = Tugas::with(['user', 'creator'])->whereNull('deleted_at');

            // If scope is personal, filter by created_by
            if ($scope === 'personal') {
                $query->where('created_by', $user->id);
            }

            return $query->orderBy('created_at', 'desc')
                ->take($limit)
                ->get();
        }

        // Employee: Show only their tasks
        return Tugas::with(['user', 'creator'])
            ->whereNull('deleted_at')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }
    /**
     * Get task trend for the last 7 days based on scope.
     */
    public function getTaskTrend(?User $user = null, string $scope = 'global'): array
    {
        $user = $user ?? auth()->user();
        $dates = [];
        $completedData = [];
        $newData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = now()->subDays($i)->locale('id')->isoFormat('D MMM');

            // New tasks created on this date
            $query = Tugas::whereNull('deleted_at')
                ->whereDate('created_at', $date);

            // Admin logic
            if ($user->isAdmin()) {
                if ($scope === 'personal') {
                    $query->where('created_by', $user->id);
                }
            } else {
                // Employee logic
                $query->where('user_id', $user->id);
            }
            $newData[] = $query->count();

            // Completed (approved) tasks on this date
            $queryCompleted = Tugas::whereNull('deleted_at')
                ->where('status', 'approved')
                ->whereDate('updated_at', $date);

            // Admin logic
            if ($user->isAdmin()) {
                if ($scope === 'personal') {
                    $queryCompleted->where('created_by', $user->id);
                }
            } else {
                // Employee logic
                $queryCompleted->where('user_id', $user->id);
            }
            $completedData[] = $queryCompleted->count();
        }

        return [
            'labels' => $dates,
            'completed' => $completedData,
            'new' => $newData
        ];
    }
}

