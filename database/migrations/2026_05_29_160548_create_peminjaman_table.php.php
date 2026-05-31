<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('peminjaman', function (Blueprint $table) {
            // Primary Key untuk transaksi peminjaman
            $table->integer('id_peminjaman')->autoIncrement();

            // Relasi (Foreign Key)
            $table->integer('user_id'); // Menghubungkan ke id_user di tabel users (Siswa yang pinjam)
            $table->integer('alat_id'); // Menghubungkan ke id_alat di tabel alat (Alat yang dipinjam)

            // DATA FORM PEMINJAMAN (Diisi saat pertama kali scan & pilih PINJAM)
            $table->text('keperluan');                     // Alasan/tujuan meminjam alat
            $table->string('guru_pengajar', 100);          // Guru yang mengajar saat jam pelajaran tersebut
            $table->integer('jumlah_pinjam')->default(1);  // Jumlah alat yang dipinjam
            $table->dateTime('waktu_pinjam');              // Tanggal & jam peminjaman

            // DATA FORM PENGEMBALIAN (Awalnya KOSONG/NULL, baru diisi saat siswa scan & pilih KEMBALI)
            $table->dateTime('waktu_kembali')->nullable();
            $table->string('foto_bukti')->nullable();      // Menyimpan nama/path file foto bukti kembali
            $table->string('kondisi_kembali', 50)->nullable(); // Kondisi alat saat dipulangkan (misal: Baik/Rusak)

            // STATUS ALUR (Untuk kontrol logika aplikasi)
            // 'dipinjam' = Alat masih dibawa siswa
            // 'kembali'  = Alat sudah dipulangkan secara sah
            $table->enum('status_transaksi', ['dipinjam', 'kembali'])->default('dipinjam');

            $table->timestamps();

            // Deklarasi Foreign Key constraints agar data antar tabel sinkron & aman
            $table->foreign('user_id')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('alat_id')->references('id_alat')->on('alat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('peminjaman');
    }
};
