<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriAlat extends Model
{
    use HasFactory;

    // 1. Definisikan nama tabel kustom
    protected $table = 'kategori_alat';

    // 2. Definisikan kustom Primary Key
    protected $primaryKey = 'id_kategori';

    // 3. Kolom yang diizinkan untuk diisi secara massal (Mass Assignment)
    protected $fillable = [
        'nama_kategori',
    ];

    // =========================================================================
    // RELASI ELOQUENT (Menghubungkan Kategori ke Tabel Alat)
    // =========================================================================

    /**
     * Hubungan ke tabel Alat
     * (Satu kategori bisa dimiliki oleh banyak jenis Alat)
     */
    public function alat(): HasMany
    {
        return $this->hasMany(Alat::class, 'kategori_id', 'id_kategori');
    }
}
