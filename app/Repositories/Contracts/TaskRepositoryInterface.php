<?php

namespace App\Repositories\Contracts;

use App\DTOs\TaskDTO;
use App\Models\Task;

interface TaskRepositoryInterface
{
    public function store(TaskDTO $taskDTO, int $userId): Task;
    public function findByID(int $taskId): Task;

    public function update(int $taskId, string $title, string $description, string $status): void;

}
