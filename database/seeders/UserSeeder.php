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
        // 1. Buat Akun Sapras SMKN 2 Tasikmalaya
        User::create([
            'name' => 'Siswa Test',
            'username' => 'siswatest', // <--- Tambahkan ini
            'email' => 'siswa@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
        ]);

        // 2. Akun untuk Jurusan
        User::create([
            'name' => 'Admin Jurusan',
            'username' => 'adminjurusan', // <--- Tambahkan ini
            'email' => 'jurusan@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'jurusan',
        ]);

        // 3. Akun untuk Manajemen Sarpras
        User::create([
            'name' => 'Tim Sarpras',
            'username' => 'timsarpras', // <--- Tambahkan ini
            'email' => 'sarpras@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'sapras',
        ]);
    }
}
