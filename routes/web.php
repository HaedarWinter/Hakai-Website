<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TugasController;


// Public routes
Route::get('/', function () {
    return view('welcome', [
        'title' => 'Beranda',
        'menuHome' => 'active',
    ]);
})->name('welcome');

// Guest routes (not authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginProses'])->name('loginProses');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin-only routes
    Route::middleware('admin')->group(function () {
        // User management
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/store', [UserController::class, 'store'])->name('store');
            Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
            Route::put('/update/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/delete/{user}', [UserController::class, 'destroy'])->name('destroy');
        });

        // Task management
        Route::prefix('tugas')->name('tugas.')->group(function () {
            Route::get('/create', [TugasController::class, 'create'])->name('create');
            Route::post('/store', [TugasController::class, 'store'])->name('store');
            Route::get('/edit/{tugas}', [TugasController::class, 'edit'])->name('edit');
            Route::put('/update/{tugas}', [TugasController::class, 'update'])->name('update');
            Route::delete('/delete/{tugas}', [TugasController::class, 'destroy'])->name('destroy');
        });
    });

    // Task routes (accessible by all authenticated users)
    Route::get('/tugas', [TugasController::class, 'index'])->name('tugas.index');
    Route::get('/tugas/{tugas}', [TugasController::class, 'show'])->name('tugas.show');
});

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
