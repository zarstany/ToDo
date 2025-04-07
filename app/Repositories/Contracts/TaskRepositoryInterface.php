<?php

namespace App\Repositories\Contracts;

use App\DTOs\TaskDTO;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function store(TaskDTO $taskDTO, int $userId): Task;
    public function allTasksByUserId(int $userId): Collection;
    public function findByID(int $taskId): Task;
    public function deleteTask(int $taskId, int $userId): void;
    public function update(int $taskId, string $title, string $description, string $status): void;

}
