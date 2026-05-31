<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanBeli extends Model
{
    use HasFactory;

    // 1. Definisikan nama tabel kustom
    protected $table = 'pengajuan_beli';

    // 2. Definisikan kustom Primary Key
    protected $primaryKey = 'id_pengajuan';

    // 3. Kolom yang diizinkan untuk diisi secara massal (Mass Assignment)
    protected $fillable = [
        'jurusan_id',
        'sapras_id',
        'nama_alat_diajukan',
        'jumlah_diajukan',
        'alasan_pembelian',
        'status_pengajuan',
        'catatan_sapras',
    ];

    // =========================================================================
    // RELASI ELOQUENT (Menghubungkan Pengajuan ke Data Master)
    // =========================================================================

    /**
     * Hubungan balik ke tabel Jurusan
     * (Pengajuan ini dibuat dan diajukan oleh satu Jurusan tertentu)
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id', 'id_jurusan');
    }

    /**
     * Hubungan balik ke tabel Sapras
     * (Pengajuan ini divalidasi/diperiksa oleh satu petugas Sapras)
     */
    public function sapras(): BelongsTo
    {
        return $this->belongsTo(Sapras::class, 'sapras_id', 'id_sapras');
    }
}
