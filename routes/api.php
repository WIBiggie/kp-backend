<?php

use App\Http\Controllers\RakController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API Routes (Bisa diakses tanpa login)
|--------------------------------------------------------------------------
*/
// Jalur untuk cek/scan QR Rak fisik menggunakan kamera HP
Route::get('/rack/scan/{code}', [RakController::class, 'scan']);


/*
|--------------------------------------------------------------------------
| Protected API Routes (Wajib membawa Token Login / Auth Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->group(function () {

    // =====================================================================
    // HAK AKSES: KHUSUS SISWA
    // =====================================================================
    Route::middleware(['role:siswa'])->group(function () {
        // Siswa melihat daftar semua alat yang tersedia untuk dipinjam
        Route::get('/student/items', [InventoryController::class, 'studentItems']);

        // Siswa melihat riwayat peminjamannya sendiri berdasarkan ID mereka
        Route::get('/student/history/{user_id}', [TrackingController::class, 'studentHistory']);

        // Siswa melakukan pengajuan pinjaman mandiri / wajib pinjam
        Route::post('/loans', [LoanController::class, 'wajibPinjam']);
    });

    // =====================================================================
    // HAK AKSES: KHUSUS JURUSAN & SARPRAS (Petugas Konfirmasi)
    // =====================================================================
    Route::middleware(['role:jurusan,manajemen'])->group(function () {
        // Mengonfirmasi atau menyetujui pinjaman barang dari siswa
        Route::put('/loans/confirm/{id}', [LoanController::class, 'konfirmasiPinjam']);
    });

    // =====================================================================
    // HAK AKSES: KHUSUS ADMIN UTAMA
    // =====================================================================
    Route::middleware(['role:manajemen'])->group(function () {
        // Melihat seluruh log aktivitas peminjaman global di sekolah
        Route::get('/admin/logs', [AdminController::class, 'globalHistory']);

        // Manajemen data master alat (Tambah & Edit Alat)
        Route::post('/admin/items', [AdminController::class, 'storeItem']);
        Route::put('/admin/items/{id}', [AdminController::class, 'updateItem']);
    });

});
