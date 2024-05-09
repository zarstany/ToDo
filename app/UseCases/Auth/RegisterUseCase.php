<?php

namespace App\UseCases\Auth;

use App\Exceptions\Auth\EmailUsedException;
use App\Exceptions\User\UserNotFoundByEmailException;
use App\Libraries\Auth\AuthUserDTO;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\UseCases\Contracts\Users\RegisterUseCaseInterface;

class RegisterUseCase implements RegisterUseCaseInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    /**
     * Register a new user or throw an exception if the email is already used.
     *
     * @param  AuthUserDTO  $authUserDTO  Data Transfer Object with user information.
     * @return User Data Transfer Object of the newly created user.
     *
     * @throws EmailUsedException if the email is already in use.
     */
    public function execute(AuthUserDTO $authUserDTO): User
    {
        try {
            $this->userRepository->findByEmail($authUserDTO->getEmail());
            $exception = new EmailUsedException();
            $exception->setEmail($authUserDTO->getEmail());
            throw $exception;
        } catch (UserNotFoundByEmailException $exception) {

            $user = $this->userRepository->create($authUserDTO);
            $token = $user->createToken('rememberToken')->plainTextToken;
            $authUserDTO->setToken($token);

            return $user;
        }
    }
}
