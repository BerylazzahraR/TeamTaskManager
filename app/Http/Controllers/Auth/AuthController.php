<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Actions\Auth\RegisterUserAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $registerAction;

    // Menghubungkan Domain Action ke Controller via Constructor
    public function __construct(RegisterUserAction $registerAction)
    {
        $this->registerAction = $registerAction;
    }

    // 1. Menampilkan Halaman Login [cite: 132, 137]
    public function showLogin()
    {
        return view('auth.login');
    }

    // 2. Memproses Logika Masuk / Login [cite: 132, 138]
    public function login(LoginRequest $request)
    {
        // Menjalankan fungsi bawaan milik LoginRequest lu (Validasi + Keamanan Brute Force)
        $request->authenticate();

        // Jika lolos, buat ulang token session agar aman dari pembajakan
        $request->session()->regenerate();

        // Redirect user ke halaman dashboard [cite: 568, 570]
        return redirect()->intended('/dashboard')
                         ->with('success', 'Selamat datang kembali, bro!');
    }

    // 3. Menampilkan Halaman Register [cite: 132, 137]
    public function showRegister()
    {
        return view('auth.register');
    }

    // 4. Memproses Logika Daftar Akun Baru [cite: 132, 138]
    public function register(RegisterRequest $request)
    {
        // Memanggil Service Layer (Action) yang sudah kita buat sebelumnya
        $this->registerAction->execute($request->validated());

        // Redirect ke form login dengan pesan sukses
        return redirect()->route('login')
                         ->with('success', 'Akun berhasil dibuat! Silakan login, bro.');
    }

    // 5. Memproses Logika Keluar Akun / Logout [cite: 18, 21]
    public function logout(Request $request)
    {
        Auth::logout();

        // Hancurkan session lama agar tidak bisa disalahgunakan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
                         ->with('success', 'Berhasil keluar akun.');
    }
}