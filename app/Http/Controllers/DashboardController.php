<?php

namespace App\Http\Controllers;

use App\Services\StatisticsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $statsService;

    public function __construct(StatisticsService $statsService)
    {
        $this->statsService = $statsService;
    }

    /**
     * Display the dashboard view.
     */
    public function index()
    {
        return view('dashboard', [
            'title' => 'Dashboard',
            'menuDashboard' => 'active',
        ]);
    }

    /**
     * Get dashboard statistics.
     */
    public function stats()
    {
        // Explicitly use request()->user() for API compatibility
        $user = request()->user();
        $scope = request('scope', 'global');

        $stats = $this->statsService->getDashboardStats($user, $scope);

        // DEBUG: Add user info to response temporarily
        $stats['_debug_user_id'] = $user ? $user->id : null;
        $stats['_debug_user_nama'] = $user ? $user->nama : null;
        $stats['_debug_user_jabatan'] = $user ? $user->jabatan : null;
        $stats['_debug_scope'] = $scope;

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get recent tasks.
     */
    public function recentTasks()
    {
        $user = request()->user();
        $scope = request('scope', 'global');

        $tasks = $this->statsService->getRecentTasks($user, 5, $scope);

        return response()->json([
            'success' => true,
            'data' => $tasks
        ]);
    }

    /**
     * Get task trend.
     */
    public function trend()
    {
        $user = request()->user();
        $scope = request('scope', 'global');

        $trend = $this->statsService->getTaskTrend($user, $scope);

        return response()->json([
            'success' => true,
            'data' => $trend
        ]);
    }

    /**
     * Get unified dashboard summary.
     */
    public function summary()
    {
        $user = request()->user();
        $scope = request('scope', 'global');

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $this->statsService->getDashboardStats($user, $scope),
                'recent_tasks' => $this->statsService->getRecentTasks($user, 5, $scope),
                'trend' => $this->statsService->getTaskTrend($user, $scope),
            ]
        ]);
    }
}
