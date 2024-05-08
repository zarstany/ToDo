<?php

namespace App\Exceptions\User;

use Exception;

class UserNotFoundByIDException extends Exception
{
    private int $userID;

    public function getUserID(): int
    {
        return $this->userID;
    }

    public function setUserID(int $userID): void
    {
        $this->userID = $userID;
    }
}
