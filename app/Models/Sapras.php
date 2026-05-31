<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sapras extends Model
{
    use HasFactory;

    // 1. Definisikan nama tabel kustom
    protected $table = 'sapras';

    // 2. Definisikan kustom Primary Key
    protected $primaryKey = 'id_sapras';

    // 3. Kolom yang diizinkan untuk diisi secara massal
    protected $fillable = [
        'user_id',
        'nip',
        'jabatan',
    ];

    // =========================================================================
    // RELASI ELOQUENT (Menghubungkan Sapras ke Tabel Lain)
    // =========================================================================

    /**
     * Hubungan balik ke tabel Users (Petugas Sapras terhubung ke satu akun User)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    /**
     * Hubungan ke tabel Pengajuan Beli
     * (Satu petugas Sapras bisa memverifikasi/menyetujui banyak pengajuan dari jurusan)
     */
    public function pengajuanBeli(): HasMany
    {
        return $this->hasMany(PengajuanBeli::class, 'sapras_id', 'id_sapras');
    }
}
