<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Rak;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class RakController extends Controller
{
    /**
     * Menampilkan semua daftar rak (Bisa diakses Sapras atau Jurusan)
     */
    public function index(): Response
    {
        // Mengambil semua data rak beserta info jurusan dan jumlah alat di dalamnya
        $daftarRak = Rak::with(['jurusan.user'])
            ->withCount('alat') // Otomatis menghitung ada berapa jenis alat di rak ini
            ->orderBy('nama_rak', 'asc')
            ->get();

        // Ambil data semua jurusan untuk pilihan/opsi drop-down saat tambah rak baru
        $allJurusan = Jurusan::all();

        return Inertia::render('Sapras/ManajemenRak', [
            'daftar_rak' => $daftarRak,
            'all_jurusan' => $allJurusan
        ]);
    }

    /**
     * Menyimpan data rak baru + Otomatis membuatkan Token QR Code unik
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi Input
        $request->validate([
            'jurusan_id' => 'required|exists:jurusan,id_jurusan',
            'nama_rak'   => 'required|string|max:50',
        ]);

        // 2. Generate Token Unik untuk isi QR Code secara otomatis
        // Hasilnya berupa string acak sepanjang 40 karakter, contoh: "rak_64f1bc9d..."
        $qrToken = 'rak_' . Str::random(40);

        // 3. Simpan ke Database
        Rak::create([
            'jurusan_id'    => $request->jurusan_id,
            'nama_rak'      => $request->nama_rak,
            'qr_code_token' => $qrToken,
        ]);

        return redirect()->back()->with('success', 'Rak baru dan token QR berhasil dibuat!');
    }

    /**
     * Menghapus data rak
     */
    public function destroy($id): RedirectResponse
    {
        $rak = Rak::findOrFail($id);
        $rak->delete();

        return redirect()->back()->with('success', 'Rak berhasil dihapus!');
    }
}
