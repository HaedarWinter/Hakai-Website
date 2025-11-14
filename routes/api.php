<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\TugasApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public API routes
Route::post('/login', [AuthApiController::class, 'login']);

// Protected API routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthApiController::class, 'logout']);

    // User API routes
    Route::apiResource('users', UserApiController::class);
    Route::get('/users/excel', [UserApiController::class, 'excel'])->name('users.excel');
    Route::get('/users/pdf', [UserApiController::class, 'pdf'])->name('users.pdf');

    // Task API routes
    Route::prefix('tugas')->group(function () {
        // Route tanpa parameter
        Route::get('/', [TugasApiController::class, 'index']);
        Route::post('/', [TugasApiController::class, 'store']);

        //
        // PERHATIAN: Route dengan path spesifik HARUS DIDEFINISIKAN SEBELUM route parameter {tugas}
        // untuk menghindari kesalahan pencocokan seperti /tugas/karyawan yang tertangkap oleh /{tugas}.
        //
        Route::get('/karyawan', [TugasApiController::class, 'karyawanList']);
        Route::get('/excel', [TugasApiController::class, 'excel'])->name('tugas.excel');
        Route::get('/pdf', [TugasApiController::class, 'pdf'])->name('tugas.pdf');

        // Route dengan parameter (route model binding akan otomatis resolve Tugas model)
        Route::get('/{tugas}', [TugasApiController::class, 'show']);
        Route::put('/{tugas}', [TugasApiController::class, 'update']);
        Route::delete('/{tugas}', [TugasApiController::class, 'destroy']);
        Route::post('/{tugas}/upload', [TugasApiController::class, 'upload']);
        Route::post('/{tugas}/approve', [TugasApiController::class, 'approve']);
        Route::post('/{tugas}/reject', [TugasApiController::class, 'reject']);
    });

    // Dashboard API routes
    Route::get('/dashboard/stats', [\App\Http\Controllers\DashboardController::class, 'stats']);
    Route::get('/dashboard/recent-tasks', [\App\Http\Controllers\DashboardController::class, 'recentTasks']);
});
