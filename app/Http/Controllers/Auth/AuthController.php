<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Fungsi untuk Jurusan membuat akun siswa baru
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_user' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:jurusan,manajemen,siswa'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'nama_user' => $request->nama_user,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status_akun' => 'aktif'
        ]);

        return response()->json([
            'message' => 'Akun berhasil dibuat oleh sistem!',
            'user' => $user
        ], 201);
    }

    // Fungsi login untuk Siswa / Jurusan / Sarpras
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Username atau password salah!'
            ], 411);
        }

        // Generate Token menggunakan Laravel Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login sukses!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'role' => $user->role,
            'user' => [
                'id_user' => $user->id_user,
                'nama_user' => $user->nama_user,
                'username' => $user->username
            ]
        ], 200);
    }
}
