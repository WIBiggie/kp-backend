<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jurusan extends Model
{
    use HasFactory;

    // 1. Definisikan nama tabel kustom
    protected $table = 'jurusan';

    // 2. Definisikan kustom Primary Key
    protected $primaryKey = 'id_jurusan';

    // 3. Definisikan kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'user_id',
        'nama_jurusan',
    ];

    // =========================================================================
    // RELASI ELOQUENT (Menghubungkan Jurusan ke Tabel Lain)
    // =========================================================================

    /**
     * Hubungan balik ke tabel Users (Satu Jurusan memiliki satu Akun User)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    /**
     * Hubungan ke tabel Siswa (Satu Jurusan memiliki banyak Siswa)
     */
    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class, 'jurusan_id', 'id_jurusan');
    }

    /**
     * Hubungan ke tabel Rak (Satu Jurusan memiliki banyak Rak Penyimpanan Alat)
     */
    public function rak(): HasMany
    {
        return $this->hasMany(Rak::class, 'jurusan_id', 'id_jurusan');
    }

    /**
     * Hubungan ke tabel Pengajuan Beli (Satu Jurusan bisa mengajukan banyak Pembelian Alat)
     */
    public function pengajuanBeli(): HasMany
    {
        return $this->hasMany(PengajuanBeli::class, 'jurusan_id', 'id_jurusan');
    }
}
