<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function getFiltered(int $teamId, array $filters)
    {
        $query = Task::where('team_id', $teamId);

        // Filter berdasarkan status tugas (todo, in_progress, done)
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter berdasarkan user pelaksana (Assignee)
        if (!empty($filters['assignee_id'])) {
            $query->where('assignee_id', $filters['assignee_id']);
        }

        return $query->orderBy('deadline', 'asc')->get();
    }

    public function create(array $data)
    {
        return Task::create($data);
    }

    public function findById(int $id)
    {
        return Task::findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        $task = $this->findById($id);
        $task->update($data);
        return $task;
    }

    public function delete(int $id)
    {
        return Task::destroy($id);
    }
}