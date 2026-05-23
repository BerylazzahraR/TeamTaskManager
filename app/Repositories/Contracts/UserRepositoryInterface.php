<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function create(array $data);
    public function findById(int $id);
    public function findByEmail(string $email);
}