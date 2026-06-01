<?php

namespace App\Http\Controllers;

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
        $user = auth()->user()->load('sapras');

        $totalAlat        = Alat::sum('stok');
        $alatRusak        = Alat::where('kondisi', 'Rusak')->count();
        $sedangDipinjam   = Peminjaman::where('status_transaksi', 'dipinjam')->count();
        $pengajuanPending = PengajuanBeli::where('status_pengajuan', 'Pending')->count();

        $pengajuanTerbaru = PengajuanBeli::with('jurusan.user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

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
        $request->validate([
            'status_pengajuan' => 'required|in:Disetujui,Ditolak',
            'catatan_sapras'   => 'nullable|string'
        ]);

        $pengajuan = PengajuanBeli::findOrFail($id);

        $pengajuan->update([
            'sapras_id'        => auth()->user()->sapras->id_sapras,
            'status_pengajuan' => $request->status_pengajuan,
            'catatan_sapras'   => $request->catatan_sapras,
        ]);

        return redirect()->back()->with('success', 'Status pengajuan berhasil diperbarui!');
    }
}
