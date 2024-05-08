<?php

namespace App\UseCases\Contracts\Users;

use App\Exceptions\Auth\EmailUsedException;
use app\Exceptions\User\UserNotFoundByIDException;
use App\Libraries\Auth\AuthUserDTO;
use App\Models\User;

interface RegisterUseCaseInterface
{
    /**
     * @throws EmailUsedException
     * @throws UserNotFoundByIDException
     */
    public function execute(AuthUserDTO $authUserDTO): User;
}
