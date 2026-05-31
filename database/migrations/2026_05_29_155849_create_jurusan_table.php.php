<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('jurusan', function (Blueprint $table) {
            $table->integer('id_jurusan')->autoIncrement(); // Primary Key INT
            $table->integer('user_id'); // Foreign key ke tabel users
            $table->string('nama_jurusan', 50);
            $table->timestamps();

            // Relasi ke id_user di tabel users
            $table->foreign('user_id')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('jurusan');
    }
};
