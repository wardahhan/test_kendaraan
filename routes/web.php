<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminDashboardController;

// --- Authentication (Tanpa Auth Middleware) ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Semua route berikut membutuhkan login ---
Route::middleware('auth')->group(function () {

    // Dashboard untuk semua user yang sudah login
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Resource umum untuk semua user login
    Route::resources([
        'vehicles' => VehicleController::class,
        'fuel-records' => \App\Http\Controllers\FuelRecordController::class,
        'service-records' => \App\Http\Controllers\ServiceRecordController::class,
        'usage-records' => \App\Http\Controllers\UsageRecordController::class,
    ]);

    // --- Route khusus ADMIN ---
    Route::middleware('admin')->group(function () {
        // Dashboard admin
        Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Pemesanan (orders) full except approve/reject
        Route::resource('orders', OrderController::class)->except(['store']);
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

        // Route untuk reports dan logs, hanya untuk role admin dan approver1
        Route::middleware('role:admin,approver1')->group(function () {
            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
            Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
        });

        // Manajemen user hanya untuk admin
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });

    // --- Route untuk ADMIN dan APPROVER (approver1, approver2) ---
    Route::middleware('approver')->group(function () {
        // Debug approval (bisa ditampilkan daftar order yang perlu approval)
        Route::get('/debug-approval', [OrderController::class, 'approval'])->name('debug.approval');

        // Action approve level 1
        Route::post('/orders/{order}/approve-level-1', [OrderController::class, 'approve'])
            ->name('orders.approveLevel1')
            ->defaults('level', 1);

        // Action approve level 2
        Route::post('/orders/{order}/approve-level-2', [OrderController::class, 'approve'])
            ->name('orders.approveLevel2')
            ->defaults('level', 2);

        // Action reject (bisa reject untuk level 1 dan 2, kirim parameter level di request)
        Route::post('/orders/{order}/reject', [OrderController::class, 'reject'])->name('orders.reject');

        // Halaman approval 
        Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
    });

        //Halaman Export
        Route::get('/orders/export', [OrderController::class, 'export'])->name('orders.export');

        //Halaman Import
        Route::post('/vehicles/import', [VehicleController::class, 'import'])->name('vehicles.import');


});
