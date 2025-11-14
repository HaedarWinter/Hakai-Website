<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function login()
    {
        return view('auth.login', [
            'title' => 'Masuk',
        ]);
    }

    /**
     * Proses login user.
     */
    public function loginProses(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'max:16'],
        ], [
            'email.required' => 'Email Tidak Boleh Kosong',
            'email.email' => 'Email Tidak Valid',
            'password.required' => 'Kata Sandi Tidak Boleh Kosong',
            'password.min' => 'Kata Sandi Minimal 8 Karakter',
            'password.max' => 'Kata Sandi Maksimal 16 Karakter',
        ]);

        // Cek kredensial
        if (!Auth::attempt($credentials, $request->filled('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau Password Salah',
            ])->onlyInput('email');
        }

        // Regenerate session biar aman (hindari session fixation)
        $request->session()->regenerate();

        $user = Auth::user();

        // Hapus token lama biar ga numpuk
        $user->tokens()->delete();

        // Buat token Sanctum baru
        $token = $user->createToken('web_session')->plainTextToken;

        // Simpan token ke session
        session(['sanctum_token' => $token]);

        return redirect()->route('dashboard')
            ->with('success', "Selamat Datang Kembali, {$user->nama}");
    }

    /**
     * Proses logout user.
     */
    public function logout(Request $request)
    {
        // Hapus semua token Sanctum milik user
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        // Logout dari Laravel session
        Auth::logout();

        // Hapus session & regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda Berhasil Logout');
    }
}
