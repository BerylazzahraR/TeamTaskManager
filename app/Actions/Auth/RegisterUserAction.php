<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    protected $userRepository;

    /**
     * Inject UserRepositoryInterface agar Action bisa memerintah Repository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Eksekusi Logika Pendaftaran User Baru
     */
    public function execute(array $data): User
    {
        // 1. Enkripsi password mentah dari form menggunakan Bcrypt bawaan Laravel
        $data['password'] = Hash::make($data['password']);

        // 2. Generate foto profil otomatis berbasis karakter kartun lucu (RoboHash) dari email user
        $data['avatar'] = 'https://robohash.org/' . md5(strtolower(trim($data['email']))) . '?set=set4';

        // 3. Perintahkan Repository untuk memasukkan data yang sudah matang ke MySQL
        return $this->userRepository->create($data);
    }
}