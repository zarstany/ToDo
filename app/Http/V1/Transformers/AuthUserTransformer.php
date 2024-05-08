<?php

namespace App\Http\V1\Transformers;

use App\Libraries\Auth\AuthUserDTO;

class AuthUserTransformer extends TransformerAbstract
{
    protected string $resource = 'auth.user-token';

    /**
     * @return array<string, int|string> $items
     */
    public function transform(AuthUserDTO $authUserDTO): array
    {
        return [
            'token' => $authUserDTO->getToken(),
            'name' => $authUserDTO->getName(),
            'email' => $authUserDTO->getEmail(),
        ];
    }
}
