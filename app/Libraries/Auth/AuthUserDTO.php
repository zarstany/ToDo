<?php

namespace App\Libraries\Auth;

use stdClass;

class AuthUserDTO extends stdClass
{
    private string $userId;
    private string $email;
    private string $name;
    private string $password;
    private string $token;


    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): AuthUserDTO
    {
        $this->token = $token;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword($password): AuthUserDTO
    {
        $this->password = $password;
        return $this;
    }



    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): AuthUserDTO
    {
        $this->name = $name;
        return $this;
    }

    public function getUserID(): string
    {
        return $this->userId;
    }

    public function setUserID(string $userId): AuthUserDTO
    {
        $this->userId = $userId;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): AuthUserDTO
    {
        $this->email = $email;

        return $this;
    }

}
