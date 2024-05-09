<?php

namespace App\UseCases\Contracts\Users;

use App\Libraries\Auth\AuthUserDTO;

interface LoginUseCaseInterface
{
    public function execute(AuthUserDTO $authUserDTO): AuthUserDTO;
}
