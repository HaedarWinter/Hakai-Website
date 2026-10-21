<?php

use App\Http\Controllers\Api\AuditLogApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SettingsApiController;
use App\Http\Controllers\Api\TrashApiController;
use App\Http\Controllers\Api\TugasApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (Business Logic)
|--------------------------------------------------------------------------
|
| These routes handle all business logic and data operations.
| All routes return JSON responses and are prefixed with /api.
|
*/

// Public API routes
Route::post('/login', [AuthApiController::class, 'login'])->middleware('login.throttle');

Route::middleware(['auth:sanctum,web'])->group(function () {
    Route::get('/global-search', [App\Http\Controllers\Api\GlobalSearchController::class, 'search']);
});

// Protected API routes (require authentication)
// Uses both web and sanctum guards for maximum compatibility
Route::middleware(['auth:sanctum,web'])->group(function () {

    // ==================== Auth ====================
    Route::post('/logout', [AuthApiController::class, 'logout']);

    // ==================== Dashboard ====================
    Route::prefix('dashboard')->group(function () {
        Route::get('/stats', [DashboardController::class, 'stats']);
        Route::get('/recent-tasks', [DashboardController::class, 'recentTasks']);
        Route::get('/trend', [DashboardController::class, 'trend']);
        Route::get('/summary', [DashboardController::class, 'summary']);
    });

    // ==================== Settings ====================
    Route::prefix('settings')->group(function () {
        Route::post('/profile', [SettingsApiController::class, 'updateProfile']);
        Route::post('/photo', [SettingsApiController::class, 'updatePhoto']);
        Route::put('/password', [SettingsApiController::class, 'updatePassword']);
    });

    // ==================== User Management (Admin Only) ====================
    Route::middleware(['admin'])->group(function () {
        Route::post('/users/{user}/toggle-super-admin', [UserApiController::class, 'toggleSuperAdmin'])->name('users.toggleSuperAdmin');
        Route::post('/users/{user}/generate-reset-token', [UserApiController::class, 'generateResetToken'])->name('users.generateResetToken');
        Route::apiResource('users', UserApiController::class);
    });

    // ==================== Trash & Audit Log ====================
    Route::middleware(['admin'])->group(function () {
        Route::get('/trash', [TrashApiController::class, 'index']);
        Route::post('/trash/{id}/restore', [TrashApiController::class, 'restore']);
        Route::delete('/trash/{id}', [TrashApiController::class, 'forceDelete']);
        Route::get('/audit-logs', [AuditLogApiController::class, 'index']);
    });

    // ==================== Task Management ====================
    Route::prefix('tugas')->group(function () {
        // List and create
        Route::get('/', [TugasApiController::class, 'index']);
        Route::post('/', [TugasApiController::class, 'store']);

        // Special routes (must be before parameter routes)
        Route::get('/karyawan', [TugasApiController::class, 'karyawanList']);

        // Trash operations (must be before parameter routes)
        Route::get('/trash/list', [TugasApiController::class, 'trash']);

        // Comments delete (must be before parameter routes)
        Route::delete('/comments/{comment}', [\App\Http\Controllers\Api\TaskCommentController::class, 'destroy']);

        // Parameter routes (route model binding)
        Route::get('/{tugas}', [TugasApiController::class, 'show']);
        Route::put('/{tugas}', [TugasApiController::class, 'update']);
        Route::delete('/{tugas}', [TugasApiController::class, 'destroy']);
        Route::post('/{tugas}/upload', [TugasApiController::class, 'upload']);
        Route::post('/{tugas}/approve', [TugasApiController::class, 'approve']);
        Route::post('/{tugas}/reject', [TugasApiController::class, 'reject']);

        // Comments (with parameter)
        Route::get('/{tugas}/comments', [\App\Http\Controllers\Api\TaskCommentController::class, 'index']);
        Route::post('/{tugas}/comments', [\App\Http\Controllers\Api\TaskCommentController::class, 'store']);

        // Trash restore/force delete (with parameter)
        Route::post('/{id}/restore', [TugasApiController::class, 'restore']);
        Route::delete('/{id}/force', [TugasApiController::class, 'forceDelete']);
    });

    // ==================== Notifications ====================
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/mark-read', [NotificationController::class, 'markNotificationsRead']);
    Route::get('/messages', [NotificationController::class, 'messages']);
    Route::post('/messages/mark-read', [NotificationController::class, 'markMessagesRead']);
});
