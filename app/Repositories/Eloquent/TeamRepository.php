<?php

namespace App\Repositories\Eloquent;

use App\Models\Team;
use App\Repositories\Contracts\TeamRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TeamRepository implements TeamRepositoryInterface
{
    /**
     * Menyimpan data workspace baru ke tabel teams [cite: 228]
     */
    public function create(array $data)
    {
        return Team::create($data);
    }

    /**
     * Memasukkan record ke pivot table team_members [cite: 24]
     */
    public function attachMember(int $teamId, int $userId, string $role)
    {
        return DB::table('team_members')->insert([
            'team_id' => $teamId,
            'user_id' => $userId,
            'role'    => $role,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Cek keaslian keanggotaan user di sebuah workspace (Return true/false)
     */
    public function isUserInTeam(int $teamId, int $userId): bool
    {
        return DB::table('team_members')
                 ->where('team_id', $teamId)
                 ->where('user_id', $userId)
                 ->exists();
    }
}