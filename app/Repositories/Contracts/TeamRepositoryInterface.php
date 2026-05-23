<?php

namespace App\Repositories\Contracts;

interface TeamRepositoryInterface
{
    /**
     * Kontrak untuk membuat tim/workspace baru di database [cite: 23, 228]
     */
    public function create(array $data);

    /**
     * Kontrak untuk memasukkan user ke pivot table team_members [cite: 24]
     */
    public function attachMember(int $teamId, int $userId, string $role);

    /**
     * Kontrak untuk mengecek apakah user terdaftar di dalam tim tertentu
     */
    public function isUserInTeam(int $teamId, int $userId): bool;
}