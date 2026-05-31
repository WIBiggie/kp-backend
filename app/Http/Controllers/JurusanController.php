<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\PengajuanBeli;
use App\Models\Rak;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class JurusanController extends Controller
{
    /**
     * Menampilkan Dashboard Utama Jurusan
     */
    public function index(): Response
    {
        // 1. Ambil data user login beserta detail info jurusannya
        $user = auth()->user()->load('jurusan');
        $idJurusan = $user->jurusan->id_jurusan;

        // 2. Ambil semua ID rak yang dimiliki oleh jurusan ini
        $idRakJurusan = Rak::where('jurusan_id', $idJurusan)->pluck('id_rak');

        // 3. Hitung statistik internal jurusan
        $totalAlatJurusan = Alat::whereIn('rak_id', $idRakJurusan)->sum('stok');
        $totalRakJurusan  = $idRakJurusan->count();
        $pengajuanPending = PengajuanBeli::where('jurusan_id', $idJurusan)
            ->where('status_pengajuan', 'Pending')
            ->count();

        // 4. Kirim data ke komponen frontend menggunakan Inertia
        return Inertia::render('Jurusan/Dashboard', [
            'auth' => [
                'user' => $user
            ],
            'statistik' => [
                'total_alat' => $totalAlatJurusan,
                'total_rak' => $totalRakJurusan,
                'pengajuan_pending' => $pengajuanPending,
            ]
        ]);
    }

    /**
     * Menampilkan Halaman Riwayat & Form Pengajuan Pembelian Alat
     */
    public function indexPengajuan(): Response
    {
        $idJurusan = auth()->user()->jurusan->id_jurusan;

        // Mengambil semua riwayat pengajuan dari jurusan ini beserta info petugas sapras yang memverifikasi
        $riwayatPengajuan = PengajuanBeli::with(['sapras.user'])
            ->where('jurusan_id', $idJurusan)
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Jurusan/PengajuanBeli', [
            'riwayat_pengajuan' => $riwayatPengajuan
        ]);
    }

    /**
     * Memproses Penyimpanan Data Pengajuan Beli Baru ke Database
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi input dari form pengajuan
        $request->validate([
            'nama_alat_diajukan' => 'required|string|max:100',
            'jumlah_diajukan'    => 'required|integer|min:1',
            'alasan_pembelian'   => 'required|string',
        ]);

        // 2. Dapatkan ID Jurusan dari user yang sedang login
        $idJurusan = auth()->user()->jurusan->id_jurusan;

        // 3. Simpan data pengajuan baru (sapras_id otomatis NULL & status otomatis Pending)
        PengajuanBeli::create([
            'jurusan_id'          => $idJurusan,
            'nama_alat_diajukan' => $request->nama_alat_diajukan,
            'jumlah_diajukan'    => $request->jumlah_diajukan,
            'alasan_pembelian'   => $request->alasan_pembelian,
        ]);

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->route('jurusan.pengajuan')->with('success', 'Pengajuan pembelian alat berhasil dikirim ke Sapras!');
    }
}
