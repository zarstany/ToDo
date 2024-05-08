<?php

namespace App\Repositories\Auth;

use App\Exceptions\User\UserNotFoundByEmailException;
use App\Exceptions\User\UserNotFoundByIDException;
use App\Libraries\Auth\AuthUserDTO;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class EloquentUserRepository implements UserRepositoryInterface
{
    /**
     * @throws UserNotFoundByEmailException
     */
    public function findByEmail(string $email): User
    {
        $userModel = new User();
        try {
            /** @var User $user */
            $user = $userModel->newQuery()->where('email', '=', $email)->firstOrFail();

            return $user;
        } catch (ModelNotFoundException) {
            $exception = new UserNotFoundByEmailException();
            $exception->setEmail($email);

            throw $exception;
        }
    }

    /**
     * @throws UserNotFoundByIDException
     */
    public function findByID(int $userID): User
    {
        try {
            return User::findOrFail($userID);
        } catch (ModelNotFoundException) {
            $exception = new UserNotFoundByIDException();
            $exception->setUserID($userID);
            throw $exception;
        }
    }

    /**
     * @param AuthUserDTO $userDTO
     */
    public function create(AuthUserDTO $userDTO): User
    {
        return User::create([
            'name' => $userDTO->getName(),
            'email' => $userDTO->getEmail(),
            'password' => $userDTO->getPassword()
        ]);

    }
}
