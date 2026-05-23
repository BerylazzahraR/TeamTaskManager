<?php

namespace App\Actions\Team;

use App\Models\Team;
use App\Repositories\Contracts\TeamRepositoryInterface;
use Illuminate\Support\Str;

class CreateTeamAction
{
    protected $teamRepository;

    // Inject TeamRepository ke dalam Action via Constructor
    public function __construct(TeamRepositoryInterface $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    /**
     * Eksekusi Logika Pembuatan Workspace Baru [cite: 241]
     */
    public function execute(int $ownerId, array $data): Team
    {
        // 1. Generate slug otomatis dari input nama workspace (misal: "Tim Beryl" jadi "tim-beryl")
        $data['slug'] = Str::slug($data['name']) . '-' . rand(1000, 9999); // Ditambah angka random biar selalu unik
        $data['owner_id'] = $ownerId;

        // 2. Perintahkan Repository untuk insert data ke tabel teams [cite: 228]
        $team = $this->teamRepository->create($data);

        // 3. Otomatis daftarkan pembuat workspace sebagai anggota dengan hak akses 'admin' [cite: 24, 242]
        $this->teamRepository->attachMember($team->id, $ownerId, 'admin');

        return $team;
    }
}