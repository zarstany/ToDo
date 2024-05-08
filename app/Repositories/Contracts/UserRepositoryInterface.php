<?php

namespace App\Repositories\Contracts;

use app\Exceptions\User\UserNotFoundByEmailException;
use app\Exceptions\User\UserNotFoundByIDException;
use App\Libraries\Auth\AuthUserDTO;
use App\Models\User;

interface UserRepositoryInterface
{


    /**
     * @throws UserNotFoundByEmailException
     */
    public function findByEmail(string $email): User;

    /**
     * @throws UserNotFoundByIDException
     */
    public function findByID(int $userID): User;

    public function create(AuthUserDTO $userDTO): User;
}
