<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'nama_user',
    'username',
    'password',
    'role',
    'email',
    'status_akun',
])]
#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // 1. Definisikan nama tabel kustom
    protected $table = 'users';

    // 2. Definisikan kustom Primary Key
    protected $primaryKey = 'id_user';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Otomatis mengamankan password saat di-input
        ];
    }

    // =========================================================================
    // RELASI ELOQUENT (Menghubungkan User ke Tabel Detail Profilnya)
    // =========================================================================

    /**
     * Hubungan User ke detail data Siswa
     */
    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class, 'user_id', 'id_user');
    }

    /**
     * Hubungan User ke detail data Jurusan
     */
    public function jurusan(): HasOne
    {
        return $this->hasOne(Jurusan::class, 'user_id', 'id_user');
    }

    /**
     * Hubungan User ke detail data Sapras
     */
    public function sapras(): HasOne
    {
        return $this->hasOne(Sapras::class, 'user_id', 'id_user');
    }
}
