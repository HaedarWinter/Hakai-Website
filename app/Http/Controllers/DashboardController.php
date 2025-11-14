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
        $stats = $this->statsService->getDashboardStats();

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
        $tasks = $this->statsService->getRecentTasks();

        return response()->json([
            'success' => true,
            'data' => $tasks
        ]);
    }
}
