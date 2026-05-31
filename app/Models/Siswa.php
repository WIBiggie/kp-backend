<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    use HasFactory;

    // 1. Definisikan nama tabel kustom
    protected $table = 'siswa';

    // 2. Definisikan kustom Primary Key
    protected $primaryKey = 'id_siswa';

    // 3. Kolom yang diizinkan untuk diisi secara massal
    protected $fillable = [
        'user_id',
        'jurusan_id',
        'nisn',
        'kelas',
    ];

    // =========================================================================
    // RELASI ELOQUENT (Menghubungkan Siswa ke Tabel Lain)
    // =========================================================================

    /**
     * Hubungan balik ke tabel Users (Siswa terhubung ke satu akun User)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    /**
     * Hubungan balik ke tabel Jurusan (Siswa berada di bawah satu Jurusan)
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id', 'id_jurusan');
    }

    /**
     * Hubungan ke tabel Peminjaman (Siswa bisa melakukan banyak transaksi peminjaman alat)
     */
    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'user_id', 'user_id');
    }
}
