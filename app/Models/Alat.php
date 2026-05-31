<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alat extends Model
{
    use HasFactory;

    // 1. Definisikan nama tabel kustom
    protected $table = 'alat';

    // 2. Definisikan kustom Primary Key
    protected $primaryKey = 'id_alat';

    // 3. Kolom yang diizinkan untuk diisi secara massal (Mass Assignment)
    protected $fillable = [
        'kode_alat',
        'kategori_id',
        'rak_id',
        'nama_alat',
        'stok',
        'kondisi',
        'qr_code',
    ];

    // =========================================================================
    // RELASI ELOQUENT (Menghubungkan Alat ke Tabel Lain)
    // =========================================================================

    /**
     * Hubungan balik ke tabel Kategori Alat
     * (Satu alat termasuk ke dalam satu kategori khusus)
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriAlat::class, 'kategori_id', 'id_kategori');
    }

    /**
     * Hubungan balik ke tabel Rak
     * (Satu alat disimpan di dalam satu rak/lokasi tertentu)
     */
    public function rak(): BelongsTo
    {
        return $this->belongsTo(Rak::class, 'rak_id', 'id_rak');
    }

    /**
     * Hubungan ke tabel Peminjaman
     * (Satu alat bisa memiliki banyak riwayat/catatan transaksi peminjaman)
     */
    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'alat_id', 'id_alat');
    }
}
