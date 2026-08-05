<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Auth\LoginController as AuthLoginController;
use Illuminate\Support\Facades\Artisan;

// Rute otomatis untuk menghapus cache Laravel di Hosting
Route::get('/sys-clear-cache', function () {
    Artisan::call('optimize:clear');
    return response()->json([
        'status' => 'success',
        'message' => 'Semua cache Laravel (config, route, view) berhasil dihapus otomatis!'
    ]);
});

// Public Login Routes
Route::get('/', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Logout Route (Auth required)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/logout', [LoginController::class, 'logout'])->middleware('auth'); // Fallback direct link

// Admin & Mentor Routes (Role authorized)
Route::middleware(['auth', 'mentor'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index']);

    // QR Code Generator
    Route::get('/buat-qr', [\App\Http\Controllers\Admin\QrGeneratorController::class, 'index'])->name('buat_qr');
    Route::post('/buat-qr', [\App\Http\Controllers\Admin\QrGeneratorController::class, 'store'])->name('buat_qr.store');
    Route::get('/buat-qr/edit/{id}', [\App\Http\Controllers\Admin\QrGeneratorController::class, 'edit'])->name('buat_qr.edit');
    Route::post('/buat-qr/update/{id}', [\App\Http\Controllers\Admin\QrGeneratorController::class, 'update'])->name('buat_qr.update');
    Route::get('/buat-qr/delete/{id}', [\App\Http\Controllers\Admin\QrGeneratorController::class, 'destroy'])->name('buat_qr.delete');

    // Kelola User & Magang (CRUD)
    Route::get('/users', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users');
    Route::post('/users', [\App\Http\Controllers\Admin\UserManagementController::class, 'store'])->name('users.store');
    Route::post('/users/update/{id}', [\App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('users.update');
    Route::get('/users/delete/{id}', [\App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('users.delete');
    Route::get('/users/detail/{id}', [\App\Http\Controllers\Admin\UserManagementController::class, 'getUserDetails'])->name('users.detail');

    // Analisis Prestasi
    Route::get('/prestasi', [\App\Http\Controllers\Admin\PrestasiController::class, 'index'])->name('prestasi');
    Route::post('/prestasi/ajax', [\App\Http\Controllers\Admin\PrestasiController::class, 'ajaxFilter'])->name('prestasi.ajax');

    // Laporan Kehadiran
    Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/export', [\App\Http\Controllers\Admin\LaporanController::class, 'export'])->name('laporan.export');
});

// Intern & Magang Routes (Role authorized)
Route::middleware(['auth', 'magang'])->prefix('magang')->name('magang.')->group(function () {
    // Dashboard Magang
    Route::get('/', [\App\Http\Controllers\Magang\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [\App\Http\Controllers\Magang\DashboardController::class, 'index']);

    // QR Scan Absensi
    Route::get('/scan', [\App\Http\Controllers\Magang\ScannerController::class, 'index'])->name('scan');
    Route::post('/scan', [\App\Http\Controllers\Magang\ScannerController::class, 'process'])->name('scan.process');

    // Riwayat Absensi
    Route::get('/riwayat', [\App\Http\Controllers\Magang\RiwayatController::class, 'index'])->name('riwayat');

    // Profile & Biodata Magang
    Route::get('/peserta', [\App\Http\Controllers\Magang\ProfileController::class, 'index'])->name('peserta');
    Route::post('/peserta', [\App\Http\Controllers\Magang\ProfileController::class, 'update'])->name('peserta.update');
});

// Rute Diagnosis Sementara untuk Membersihkan Cache di Hosting Gratis
Route::get('/clear-cache', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        return "<h3>Sukses! Semua cache Laravel (config, route, view, cache) berhasil dibersihkan.</h3><p>Silakan coba login ulang ke sistem sekarang.</p>";
    } catch(\Throwable $e) {
        return "<h3>Gagal membersihkan cache:</h3><p>" . $e->getMessage() . "</p>";
    }
});
