<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Akun Siswa
        User::create([
            'nama_user' => 'Siswa Test',   // <-- Ditambahkan agar database tidak error
            'username'  => 'siswatest',
            'email'     => 'siswa@gmail.com',
            'password'  => Hash::make('password123'),
            'role'      => 'siswa',
        ]);

        // 2. Akun untuk Jurusan
        User::create([
            'nama_user' => 'Admin Jurusan SKN2', // <-- Ditambahkan agar database tidak error
            'username'  => 'adminjurusan',
            'email'     => 'jurusan@gmail.com',
            'password'  => Hash::make('password123'),
            'role'      => 'jurusan',
        ]);

        // 3. Akun untuk Manajemen Sarpras
        User::create([
            'nama_user' => 'Tim Sarpras SMKN 2', // <-- Ditambahkan agar database tidak error
            'username'  => 'timsarpras',
            'email'     => 'sarpras@gmail.com',
            'password'  => Hash::make('password123'),
            'role'      => 'manajemen', // Sesuai dengan nama role di web.php Anda sebelumnya
        ]);
    }
}
