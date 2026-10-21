<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Navigation Only)
|--------------------------------------------------------------------------
|
| These routes handle page navigation and view rendering only.
| All business logic and data operations are handled by API routes.
|
*/

// Public routes - Navigation only
Route::get('/', function () {
    return view('welcome', [
        'title' => 'Beranda',
        'menuHome' => 'active',
    ]);
})->name('welcome');

// Guest routes - Views only
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginProses'])->middleware('login.throttle')->name('loginProses');

    // Password Reset Routes
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
});

// Authenticated routes - Views only
Route::middleware('auth.check')->group(function () {
    // Authenticated routes - Views only
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Task views (accessible by all authenticated users)
    Route::get('/tugas', [TugasController::class, 'index'])->name('tugas.index');
    Route::get('/tugas/trash', [TugasController::class, 'trash'])->name('tugas.trash');
    Route::get('/tugas/{tugas}', [TugasController::class, 'show'])->name('tugas.show');

    // Task Exports (File Downloads - require Session Auth)
    Route::get('/tugas/export/excel', [\App\Http\Controllers\Api\TugasApiController::class, 'excel'])->name('tugas.excel');
    Route::get('/tugas/export/pdf', [\App\Http\Controllers\Api\TugasApiController::class, 'pdf'])->name('tugas.pdf');

    // Profile & Settings
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/{user}', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Legacy Settings Route Redirect (Optional, or keep for backward compatibility until refactor is one)
    Route::get('/settings', function () {
        return redirect()->route('profile.index');
    })->name('settings.index');

    // Admin-only views
    Route::middleware('admin')->group(function () {
        Route::get('/user', [UserController::class, 'index'])->name('user.index');

        // User Exports (File Downloads - Admin Only)
        Route::get('/users/excel', [\App\Http\Controllers\Api\UserApiController::class, 'excel'])->name('users.excel');
        Route::get('/users/pdf', [\App\Http\Controllers\Api\UserApiController::class, 'pdf'])->name('users.pdf');

        // Audit Fixes Phase 2
        Route::get('/trash', [App\Http\Controllers\Admin\TrashController::class, 'index'])->name('admin.trash.index');
        Route::get('/audit-log', [App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('admin.audit-log.index');
    });
});

// Logout route (form submission)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
})->middleware('auth');
