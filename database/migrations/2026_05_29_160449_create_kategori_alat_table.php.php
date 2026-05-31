<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kategori_alat', function (Blueprint $table) {
            // Mengubah Primary Key menjadi id_kategori (INT)
            $table->integer('id_kategori')->autoIncrement();
            $table->string('nama_kategori', 50); // Contoh: Alat Ukur, Handtools, Elektronik
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('kategori_alat');
    }
};
