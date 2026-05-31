<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('siswa', function (Blueprint $table) {
            $table->integer('id_siswa')->autoIncrement(); // Primary Key INT
            $table->integer('user_id'); // Foreign key ke tabel users
            $table->integer('jurusan_id'); // Foreign key ke tabel jurusan
            $table->string('nisn', 20)->unique();
            $table->string('kelas', 20);
            $table->timestamps();

            // Setup Relasi
            $table->foreign('user_id')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('jurusan_id')->references('id_jurusan')->on('jurusan')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('siswa');
    }
};
