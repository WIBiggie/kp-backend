<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pengajuan_beli', function (Blueprint $table) {
            // Mengubah ke kustom Primary Key (INT)
            $table->integer('id_pengajuan')->autoIncrement();

            // Relasi ke jurusan (Pihak yang mengajukan)
            $table->integer('jurusan_id');

            // Relasi ke sapras (Pihak yang memvalidasi / menyetujui)
            // Di-set nullable karena saat pertama diajukan, belum ada petugas Sapras yang memeriksa
            $table->integer('sapras_id')->nullable();

            $table->string('nama_alat_diajukan', 100);
            $table->integer('jumlah_diajukan');
            $table->text('alasan_pembelian');

            // Status Pengajuan
            $table->enum('status_pengajuan', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending');

            // Feedback dari Sapras
            $table->text('catatan_sapras')->nullable();
            $table->timestamps();

            // Set Foreign Key Constraints secara manual
            $table->foreign('jurusan_id')->references('id_jurusan')->on('jurusan')->onDelete('cascade');
            $table->foreign('sapras_id')->references('id_sapras')->on('sapras')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('pengajuan_beli');
    }
};
