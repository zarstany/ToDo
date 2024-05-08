<?php

namespace App\UseCases\Auth;

use App\Exceptions\Auth\InvalidCredentialsException;
use app\Exceptions\User\UserNotFoundByEmailException;
use App\Libraries\Auth\AuthUserDTO;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\UseCases\Contracts\Users\LoginUseCaseInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginUseCase implements LoginUseCaseInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @throws UserNotFoundByEmailException
     * @throws InvalidCredentialsException
     */
    public function execute(AuthUserDTO $authUserDTO): AuthUserDTO
    {
        $userOrNull = $this->userRepository->findByEmail($authUserDTO->getEmail());

        if (!$userOrNull) {
            throw new InvalidCredentialsException('Invalid credentials.');
        }

        if (!Hash::check($authUserDTO->getPassword(), $userOrNull->password)) {
            throw new InvalidCredentialsException('Incorrect password or email.');
        }

        $user = Auth::login($userOrNull);
        $token = $userOrNull->createToken('rememberToken')->plainTextToken;
        $authUserDTO->setToken($token);
        $authUserDTO->setName($userOrNull->name);
        return $authUserDTO;
    }
}
