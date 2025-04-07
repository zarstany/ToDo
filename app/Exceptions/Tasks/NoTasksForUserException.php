<?php

namespace App\Exceptions\Tasks;

use Exception;

class NoTasksForUserException extends Exception
{
    private int $userId;

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }
}
