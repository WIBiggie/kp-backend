<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SiswaController extends Controller
{
    /**
     * Menampilkan Dashboard Utama Siswa
     */
    public function index(): Response
    {
        // 1. Ambil data user yang sedang login beserta detail info siswanya dan jurusannya
        $user = auth()->user()->load(['siswa.jurusan']);

        // 2. Ambil riwayat peminjaman alat milik siswa ini
        // Kita menggunakan user_id untuk mencocokkan transaksi di tabel peminjaman
        $riwayatPeminjaman = Peminjaman::with(['alat'])
            ->where('user_id', $user->id_user)
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Hitung ringkasan data untuk statistik di halaman dashboard siswa
        $totalPinjam = $riwayatPeminjaman->count();

        $belumKembali = Peminjaman::where('user_id', $user->id_user)
            ->where('status_transaksi', 'dipinjam')
            ->count();

        // 4. Kirim semua data di atas ke komponen View (React/Vue) menggunakan Inertia
        return Inertia::render('Siswa/Dashboard', [
            'auth' => [
                'user' => $user
            ],
            'riwayat' => $riwayatPeminjaman,
            'statistik' => [
                'total_pinjam' => $totalPinjam,
                'belum_kembali' => $belumKembali,
            ]
        ]);
    }
}
