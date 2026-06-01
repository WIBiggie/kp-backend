<?php

use App\Http\Controllers\RakController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\Auth\AuthController; // 🌟 1. Pastikan mengimport AuthController kamu di sini
use Illuminate\Support\Facades\Route;

// Ubah yang tadinya return view('welcome') menjadi return response JSON
Route::get('/', function () {
    return response()->json([
        'message' => 'Selamat datang di KP-Backend API',
        'status' => 'Connected to Database',
        'laravel_version' => app()->version()
    ]);
});

/*
|--------------------------------------------------------------------------
| Public API Routes (Bisa diakses tanpa login)
|--------------------------------------------------------------------------
*/
// Jalur untuk cek/scan QR Rak fisik menggunakan kamera HP
Route::get('/rack/scan/{code}', [RakController::class, 'scan']);

// 🌟 2. TAMBAHKAN BARIS INI SUPAYA FRONTEND BISA MENGETUK PROSES LOGIN
Route::post('/login', [AuthController::class, 'login']);


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
        Route::get('/student/items', [InventoryController::class, 'studentItems']);
        Route::get('/student/history/{user_id}', [TrackingController::class, 'studentHistory']);
        Route::post('/loans', [LoanController::class, 'wajibPinjam']);
    });

    // =====================================================================
    // HAK AKSES: KHUSUS JURUSAN & SARPRAS (Petugas Konfirmasi)
    // =====================================================================
    Route::middleware(['role:jurusan,manajemen'])->group(function () {
        Route::put('/loans/confirm/{id}', [LoanController::class, 'konfirmasiPinjam']);
    });

    // =====================================================================
    // HAK AKSES: KHUSUS ADMIN UTAMA
    // =====================================================================
    Route::middleware(['role:manajemen'])->group(function () {
        Route::get('/admin/logs', [AdminController::class, 'globalHistory']);
        Route::post('/admin/items', [AdminController::class, 'storeItem']);
        Route::put('/admin/items/{id}', [AdminController::class, 'updateItem']);
    });

});
