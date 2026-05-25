<?php

namespace App\Repositories\Contracts;

interface TaskRepositoryInterface
{
    public function getFiltered(int $teamId, array $filters);
    public function create(array $data);
    public function findById(int $id);
    public function update(int $id, array $data);
    public function delete(int $id);
}