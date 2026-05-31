<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rak extends Model
{
    use HasFactory;

    // 1. Definisikan nama tabel kustom
    protected $table = 'rak';

    // 2. Definisikan kustom Primary Key
    protected $primaryKey = 'id_rak';

    // 3. Kolom yang diizinkan untuk diisi secara massal (Mass Assignment)
    protected $fillable = [
        'jurusan_id',
        'nama_rak',
        'qr_code_token',
    ];

    // =========================================================================
    // RELASI ELOQUENT (Menghubungkan Rak ke Tabel Lain)
    // =========================================================================

    /**
     * Hubungan balik ke tabel Jurusan
     * (Satu rak merupakan aset atau berada di bawah naungan satu Jurusan)
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id', 'id_jurusan');
    }

    /**
     * Hubungan ke tabel Alat
     * (Satu rak bisa menampung/berisi banyak jenis Alat)
     */
    public function alat(): HasMany
    {
        return $this->hasMany(Alat::class, 'rak_id', 'id_rak');
    }
}
