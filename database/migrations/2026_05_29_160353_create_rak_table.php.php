<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rak', function (Blueprint $table) {
            // Mengubah Primary Key menjadi id_rak (INT)
            $table->integer('id_rak')->autoIncrement();

            // Relasi ke tabel jurusan (jika Anda memiliki tabel master 'jurusan')
            $table->integer('jurusan_id');

            $table->string('nama_rak', 50); // Contoh: Rak A-1, Lemari Alat Ukur
            $table->string('qr_code_token', 100)->unique(); // Token unik isi QR Code rak
            $table->timestamps();

            // Opsional: Aktifkan ini jika Anda sudah membuat tabel 'jurusan' dengan PK 'id_jurusan'
            // $table->foreign('jurusan_id')->references('id_jurusan')->on('jurusan')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('rak');
    }
};
