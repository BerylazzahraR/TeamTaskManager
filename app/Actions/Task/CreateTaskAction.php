<?php

namespace App\Actions\Task;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\Contracts\TeamRepositoryInterface;
use Illuminate\Validation\ValidationException;

class CreateTaskAction
{
    protected $taskRepository;
    protected $teamRepository;

    // Inject kedua repository (Task & Team) via Constructor
    public function __construct(
        TaskRepositoryInterface $taskRepository,
        TeamRepositoryInterface $teamRepository
    ) {
        $this->taskRepository = $taskRepository;
        $this->teamRepository = $teamRepository;
    }

    /**
     * Eksekusi Logika Pembuatan & Delegasi Tugas Tim
     */
    public function execute(array $data): Task
    {
        // Aturan Bisnis: Jika tugas ini ditunjuk/didelegasikan ke seseorang (assignee_id tidak kosong)
        if (!empty($data['assignee_id'])) {
            // Cek ke pivot table lewat TeamRepository, apakah orang ini emang member di tim ini?
            $isMember = $this->teamRepository->isUserInTeam($data['team_id'], $data['assignee_id']);
            
            // Jika ternyata penyusup / bukan anggota tim, langsung tolak dan lempar error!
            if (!$isMember) {
                throw ValidationException::withMessages([
                    'assignee_id' => 'User yang ditunjuk bukan bagian dari anggota tim di workspace ini'
                ]);
            }
        }

        // Set status bawaan saat tugas pertama kali dibuat menjadi 'todo'
        $data['status'] = $data['status'] ?? 'todo';

        // Jika lolos pengecekan aturan bisnis, perintahkan repository simpan ke MySQL
        return $this->taskRepository->create($data);
    }
}