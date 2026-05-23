<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan mengakses request ini.
     */
    public function authorize(): bool
    {
        return true; // Ubah jadi true agar semua pengunjung bisa mendaftar akun
    }

    /**
     * Aturan validasi ketat untuk Form Registrasi
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            // Email wajib diisi, berformat email, maksimal 100 karakter, dan tidak boleh kembar di tabel users
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            // Password wajib diisi, minimal 8 karakter, dan wajib COCOK dengan input 'password_confirmation'
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}