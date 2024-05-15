<?php

namespace App\Exceptions\Tasks;

use Exception;

class TaskNotFoundByIdException extends Exception
{
    private int $taskId;

    public function getTaskId(): int
    {
        return $this->taskId;
    }

    public function setTaskId(int $taskId): void
    {
        $this->taskId = $taskId;
    }
}
