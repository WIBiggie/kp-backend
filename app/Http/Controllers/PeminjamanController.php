<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PeminjamanController extends Controller
{
    /**
     * 1. Menampilkan Halaman Kamera Scanner QR di HP Siswa
     */
    public function showScanner(): Response
    {
        return Inertia::render('Siswa/ScanQR');
    }

    /**
     * 2. Proses Pinjam Instan (Langsung Potong Stok, Tanpa Validasi Sapras)
     */
    public function prosesPinjam(Request $request): RedirectResponse
    {
        // Validasi input token QR dari kamera
        $request->validate([
            'kode_alat' => 'required|exists:alat,kode_alat',
        ]);

        // Cari alat berdasarkan kode QR yang di-scan
        $alat = Alat::where('kode_alat', $request->kode_alat)->firstOrFail();

        // Cek apakah stok alat masih ada
        if ($alat->stok <= 0) {
            return redirect()->back()->with('error', 'Gagal pinjam! Stok alat saat ini sedang habis.');
        }

        // Cek apakah kondisi alat layak pakai
        if ($alat->kondisi === 'Rusak') {
            return redirect()->back()->with('error', 'Gagal pinjam! Alat ini sedang dalam kondisi rusak.');
        }

        // --- PROSES INSTAN DIMULAI ---

        // A. Kurangi stok alat secara langsung
        $alat->decrement('stok', 1); // Mengurangi kolom stok sebanyak 1

        // B. Catat langsung ke tabel peminjaman dengan status 'dipinjam'
        Peminjaman::create([
            'user_id'          => auth()->user()->id_user, // ID Siswa yang login
            'alat_id'          => $alat->id_alat,
            'tanggal_pinjam'   => now(), // Detik ini juga langsung aktif
            'tanggal_kembali'  => null,  // Belum dikembalikan
            'status_transaksi' => 'dipinjam',
        ]);

        return redirect()->route('siswa.dashboard')->with('success', 'Berhasil! Alat ' . $alat->nama_alat . ' siap digunakan.');
    }
}
