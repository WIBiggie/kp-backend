<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('alat', function (Blueprint $table) {
    $table->integer('id_alat')->autoIncrement();
    $table->string('kode_alat', 50)->unique();
    $table->integer('kategori_id'); // Relasi ke kategori_alat
    $table->integer('rak_id');      // Relasi ke tabel rak (Menggantikan kolom lokasi)
    $table->string('nama_alat', 100);
    $table->integer('stok');
    $table->string('kondisi', 50)->default('Baik');
    $table->text('qr_code')->nullable();
    $table->timestamps();

    $table->foreign('kategori_id')->references('id_kategori')->on('kategori_alat')->onDelete('cascade');
    $table->foreign('rak_id')->references('id_rak')->on('rak')->onDelete('cascade');
});
    }

    public function down(): void {
        Schema::dropIfExists('alat');
    }
};
