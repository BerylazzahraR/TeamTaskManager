<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Menyimpan data user baru ke database MySQL
     */
    public function create(array $data)
    {
        return User::create($data);
    }

    /**
     * Mencari user berdasarkan ID spesifik
     */
    public function findById(int $id)
    {
        return User::findOrFail($id);
    }

    /**
     * Mencari user berdasarkan email (buat check login/undangan tim)
     */
    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }
}