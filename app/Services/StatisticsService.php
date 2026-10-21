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

        $nonAdminUsers = $users->whereNotIn('jabatan', ['Admin'])->values();

        // Callers normally eager-load task counts. Load them once here when missing.
        if ($nonAdminUsers->isNotEmpty() && ! array_key_exists('total_tugas', $nonAdminUsers->first()->getAttributes())) {
            $nonAdminUsers->loadCount([
                'tugas as total_tugas',
                'tugas as tugas_selesai' => fn ($query) => $query->where('status', 'approved'),
            ]);
        }

        $tugasSelesai = $nonAdminUsers->filter(fn ($u) => ($u->total_tugas ?? 0) > 0 && ($u->tugas_selesai ?? 0) > 0)->count();
        $tugasBelumSelesai = $nonAdminUsers->count() - $tugasSelesai;

        $userDitugaskan = $nonAdminUsers->filter(fn ($u) => ($u->total_tugas ?? 0) > 0)->count();
        $userBelumDitugaskan = $nonAdminUsers->count() - $userDitugaskan;

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
            'updated_at' => now()->format('d F Y H:i:s'),
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

        if (! $user) {
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
        $row = (clone $query)->selectRaw(
            'COUNT(*) as total,
             SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as approved,
             SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending,
             SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as submitted,
             SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as rejected',
            [Tugas::STATUS_APPROVED, Tugas::STATUS_PENDING, Tugas::STATUS_SUBMITTED, Tugas::STATUS_REJECTED]
        )->first();

        return [
            'total_tugas' => (int) ($row->total ?? 0),
            'total_users' => $totalUsers,
            'total_selesai' => (int) ($row->approved ?? 0),
            'total_pending' => (int) ($row->pending ?? 0),
            'total_submitted' => (int) ($row->submitted ?? 0),
            'total_rejected' => (int) ($row->rejected ?? 0),
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

        $start = now()->subDays(6)->startOfDay();

        // One grouped query per series instead of two queries per day (14 per request).
        $newQuery = Tugas::where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day');

        $completedQuery = Tugas::where('status', Tugas::STATUS_APPROVED)
            ->where('updated_at', '>=', $start)
            ->selectRaw('DATE(updated_at) as day, COUNT(*) as total')
            ->groupBy('day');

        if ($user->isAdmin()) {
            if ($scope === 'personal') {
                $newQuery->where('created_by', $user->id);
                $completedQuery->where('created_by', $user->id);
            }
        } else {
            $newQuery->where('user_id', $user->id);
            $completedQuery->where('user_id', $user->id);
        }

        $newByDay = $newQuery->pluck('total', 'day');
        $completedByDay = $completedQuery->pluck('total', 'day');

        $dates = [];
        $completedData = [];
        $newData = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $key = $day->format('Y-m-d');

            $dates[] = $day->locale('id')->isoFormat('D MMM');
            $completedData[] = (int) ($completedByDay[$key] ?? 0);
            $newData[] = (int) ($newByDay[$key] ?? 0);
        }

        return [
            'labels' => $dates,
            'completed' => $completedData,
            'new' => $newData,
        ];
    }
}
