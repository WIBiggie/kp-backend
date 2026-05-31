<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\SaprasController; // Memastikan nama controller konsisten dengan tabel sapras
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\RakController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Landing Page utama bawaan Laravel Breeze
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// ROUTE REDIRECTOR DASHBOARD UTAMA (Bawaan Breeze yang disesuaikan Multi-role)
Route::get('/dashboard', function () {
    $role = auth()->user()->role;

    if ($role === 'siswa') {
        return redirect('/siswa/dashboard');
    } elseif ($role === 'jurusan') {
        return redirect('/jurusan/dashboard');
    } elseif ($role === 'manajemen') {
        return redirect('/sapras/dashboard');
    }

    abort(403, 'Role tidak dikenali.');
})->middleware(['auth', 'verified'])->name('dashboard');


// =========================================================================
// SEMUA ROUTE DI BAWAH INI WAJIB LOGIN TERLEBIH DAHULU (AUTH)
// =========================================================================
Route::middleware(['auth'])->group(function () {

    // KELOMPOK 1: Khusus Role Siswa
Route::middleware(['role:siswa'])->group(function () {
    Route::get('/siswa/dashboard', [SiswaController::class, 'index'])->name('siswa.dashboard');

    // Membuka halaman kamera scan
    Route::get('/siswa/scan', [PeminjamanController::class, 'showScanner'])->name('siswa.scan');

    // Menembak data hasil scan untuk langsung pinjam barang
    Route::post('/siswa/pinjam', [PeminjamanController::class, 'prosesPinjam'])->name('siswa.pinjam');
});

    // KELOMPOK 2: Khusus Pihak Jurusan
Route::middleware(['role:jurusan'])->group(function () {
    // Menampilkan halaman form & riwayat milik jurusannya sendiri
    Route::get('/jurusan/pengajuan', [JurusanController::class, 'indexPengajuan'])->name('jurusan.pengajuan');
    // Memproses pembuatan data PengajuanBeli baru
    Route::post('/jurusan/pengajuan/kirim', [JurusanController::class, 'store']);
    });

    // KELOMPOK 3: Khusus Pihak Sapras (Manajemen)
Route::middleware(['role:manajemen'])->group(function () {
    // Menampilkan semua daftar pengajuan masuk dari seluruh jurusan
    Route::get('/sapras/validasi', [SaprasController::class, 'indexValidasi'])->name('sapras.validasi');
    // Memproses update status PengajuanBeli (Setuju/Tolak) + Catatan feedback
    Route::post('/sapras/validasi/{id}', [SaprasController::class, 'verifikasi']);
});

    // Di dalam kelompok Route::middleware(['role:manajemen'])
Route::get('/sapras/rak', [RakController::class, 'index'])->name('sapras.rak.index');
Route::post('/sapras/rak', [RakController::class, 'store'])->name('sapras.rak.store');
Route::delete('/sapras/rak/{id}', [RakController::class, 'destroy'])->name('sapras.rak.destroy');

    // ==================== ROUTE PROFILE UTAMA (BAWAAN BREEZE) ====================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Memuat sistem login, register, dan logout otomatis dari Laravel Breeze
require __DIR__.'/auth.php';
