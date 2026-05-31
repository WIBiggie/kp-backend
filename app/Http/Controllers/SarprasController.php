<?php

namespace App\Http\Controllers; // Sesuaikan jika namespace bawaan Anda berbeda, biasanya App\Http\Controllers

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\PengajuanBeli;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class SaprasController extends Controller
{
    /**
     * Menampilkan Dashboard Utama Sapras
     */
    public function index(): Response
    {
        // 1. Ambil data petugas sapras yang sedang login beserta informasi akunnya
        $user = auth()->user()->load('sapras');

        // 2. Hitung statistik ringkas untuk widget dashboard
        $totalAlat        = Alat::sum('stok');
        $alatRusak        = Alat::where('kondisi', 'Rusak')->count();
        $sedangDipinjam   = Peminjaman::where('status_transaksi', 'dipinjam')->count();
        $pengajuanPending = PengajuanBeli::where('status_pengajuan', 'Pending')->count();

        // 3. Ambil 5 riwayat pengajuan pembelian terbaru untuk diletakkan di preview dashboard
        $pengajuanTerbaru = PengajuanBeli::with('jurusan.user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 4. Kirim data ke komponen frontend menggunakan Inertia
        return Inertia::render('Sapras/Dashboard', [
            'auth' => [
                'user' => $user
            ],
            'statistik' => [
                'total_alat'        => $totalAlat,
                'alat_rusak'        => $alatRusak,
                'sedang_dipinjam'   => $sedangDipinjam,
                'pengajuan_pending' => $pengajuanPending,
            ],
            'pengajuan_terbaru' => $pengajuanTerbaru
        ]);
    }

    /**
     * Menampilkan Semua Daftar Validasi Pengajuan Pembelian dari Jurusan
     */
    public function indexValidasi(): Response
    {
        // Mengambil semua data pengajuan beserta informasi jurusan yang mengajukan
        $semuaPengajuan = PengajuanBeli::with(['jurusan.user', 'sapras.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Sapras/ValidasiPengajuan', [
            'daftar_pengajuan' => $semuaPengajuan
        ]);
    }

    /**
     * Memproses Verifikasi (Setuju / Tolak) Pengajuan dari Jurusan
     */
    public function verifikasi(Request $request, $id): RedirectResponse
    {
        // 1. Validasi input yang masuk dari form
        $request->validate([
            'status_pengajuan' => 'required|in:Disetujui,Ditolak',
            'catatan_sapras'   => 'nullable|string'
        ]);

        // 2. Cari data pengajuan berdasarkan ID kustomnya
        $pengajuan = PengajuanBeli::findOrFail($id);

        // 3. Update data pengajuan
        $pengajuan->update([
            'sapras_id'        => auth()->user()->sapras->id_sapras, // ID petugas sapras yang meninjau
            'status_pengajuan' => $request->status_pengajuan,        // 'Disetujui' atau 'Ditolak'
            'catatan_sapras'   => $request->catatan_sapras,          // Feedback/Alasan
        ]);

        // 4. Kembali ke halaman sebelumnya dengan membawa pesan sukses
        return redirect()->back()->with('success', 'Status pengajuan berhasil diperbarui!');
    }
}
