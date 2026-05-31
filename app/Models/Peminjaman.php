<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    use HasFactory;

    // 1. Definisikan nama tabel kustom
    protected $table = 'peminjaman';

    // 2. Definisikan kustom Primary Key
    protected $primaryKey = 'id_peminjaman';

    // 3. Kolom yang diizinkan untuk diisi secara massal (Mass Assignment)
    protected $fillable = [
        'user_id',
        'alat_id',
        'keperluan',
        'guru_pengajar',
        'jumlah_pinjam',
        'waktu_pinjam',
        'waktu_kembali',
        'foto_bukti',
        'kondisi_kembali',
        'status_transaksi',
    ];

    /**
     * Get the attributes that should be cast.
     * Mengubah format string datetime dari database menjadi objek Carbon secara otomatis
     */
    protected function casts(): array
    {
        return [
            'waktu_pinjam' => 'datetime',
            'waktu_kembali' => 'datetime',
        ];
    }

    // =========================================================================
    // RELASI ELOQUENT (Menghubungkan Transaksi ke Data Master)
    // =========================================================================

    /**
     * Hubungan balik ke tabel Users
     * (Transaksi peminjaman ini dimiliki/dilakukan oleh satu akun User/Siswa)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    /**
     * Hubungan balik ke tabel Alat
     * (Transaksi peminjaman ini merujuk pada satu master Alat yang dipinjam)
     */
    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class, 'alat_id', 'id_alat');
    }
}
