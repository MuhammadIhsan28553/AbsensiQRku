<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\ReportController; // Pastikan ini ada
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\ScheduleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Rute Profil & Scan QR
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/scan', [ScanController::class, 'index'])->name('scan');
    Route::post('/scan/record', [ScanController::class, 'record'])->name('scan.record');

    // Rute Pengajuan Izin/Cuti untuk Pengguna
    Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave.index');
    Route::get('/leave-requests/create', [LeaveRequestController::class, 'create'])->name('leave.create');
    Route::post('/leave-requests', [LeaveRequestController::class, 'store'])->name('leave.store');
});

Route::middleware(['auth', 'can:isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    // Rute Dashboard Admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Rute Manajemen Pengguna
    Route::get('/users/{user}/qr-download', [AdminController::class, 'downloadQr'])->name('users.downloadQr');
    Route::resource('users', AdminController::class);

    // Rute Riwayat Absensi
    Route::get('/attendances', [AdminController::class, 'attendances'])->name('attendances.index');
    Route::get('/attendances/export', [AdminController::class, 'exportAttendances'])->name('attendances.export');
    Route::get('/attendances/create', [AdminController::class, 'createAttendance'])->name('attendances.create');
    Route::post('/attendances', [AdminController::class, 'storeAttendance'])->name('attendances.store');

    // Rute Pengaturan Jadwal
    Route::get('/settings', [AdminController::class, 'showSettings'])->name('settings.show');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

    Route::get('/leave-requests', [AdminController::class, 'listLeaveRequests'])->name('leave.list');
    Route::patch('/leave-requests/{leaveRequest}', [AdminController::class, 'updateLeaveRequest'])->name('leave.update');

     Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // RUTE BARU UNTUK EXPORT LAPORAN
    Route::get('/reports/export', [ReportController::class, 'exportReport'])->name('reports.export');

    Route::resource('shifts', ShiftController::class);

    Route::get('schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::post('schedules', [ScheduleController::class, 'store'])->name('schedules.store');
});

require __DIR__.'/auth.php';
