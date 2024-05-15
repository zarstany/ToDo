<?php

namespace App\UseCases\Contracts\Tasks;

interface UpdateTaskUseCaseInterface
{
    public function execute(int $taskId, string $title, string $description, string $status, $userId): void;
}
