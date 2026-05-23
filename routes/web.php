<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Halaman utama welcome default Laravel
Route::get('/', function () {
    return view('welcome');
});

// ============================================================================
// CUSTOM GUEST ROUTES: Untuk pengunjung yang BELUM LOGIN (Menggunakan Custom Auth Kita)
// ============================================================================
Route::middleware('guest')->group(function () {
    // Halaman & Proses Login Custom
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Halaman & Proses Daftar Akun Custom
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

// ============================================================================
// CUSTOM AUTH ROUTES: Untuk user yang SUDAH LOGIN
// ============================================================================
Route::middleware('auth')->group(function () {
    // Proses Logout Custom
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Halaman Dashboard Utama Sementara (Target Ringkasan Statistik)
    Route::get('/dashboard', function () {
        return '
            <div style="font-family: sans-serif; padding: 40px; text-align: center;">
                <h1 style="color: #4f46e5;">Sukses Menembus Dashboard, Bro Beryl!</h1>
                <p>Custom Authentication berlapis (Action & Repository Pattern) lu berjalan 100% aman dan sinkron.</p>
                <hr style="margin: 20px auto; max-width: 400px; border-color: #e2e8f0;">
                <form action="'.route('logout').'" method="POST">
                    '.csrf_field().'
                    <button type="submit" style="background-color: #ef4444; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: bold;">
                        Logout Keluar Akun
                    </button>
                </form>
            </div>
        ';
    })->name('dashboard');

    // Rute Profile bawaan yang bisa lu simpan/pakai nanti jika butuh
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// KONTROL PENTING: Kita matikan / comment rute bawaan Breeze asli 
// supaya flow aplikasi murni lewat Custom Code yang lu pahami sendiri.
// require __DIR__.'/auth.php';