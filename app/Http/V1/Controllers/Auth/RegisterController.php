<?php

namespace App\Http\V1\Controllers\Auth;

use App\Http\V1\Controllers\Controller;
use App\Http\V1\Responses\Auth\RegisterErrorResponse;
use App\Http\V1\Transformers\AuthUserTransformer;
use App\Libraries\Auth\AuthUserDTO;
use App\Libraries\Responders\Contracts\APIResponseInterface;
use App\Libraries\Responders\HttpObjectDTO;
use App\UseCases\Contracts\Users\RegisterUseCaseInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class RegisterController
{
    public function __construct(
        private readonly RegisterUseCaseInterface $registerUserUseCase,
        private readonly APIResponseInterface $apiResponse,
        private readonly RegisterErrorResponse $registerErrorResponse
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'name' => 'required|regex:/^[\pL\s\-]+$/u|min:2|max:25',
        ]);

        if ($validator->fails()) {
            Log::error('Parameters invalid', ['Register' => 'parameters invalid for register']);

            return $this->apiResponse->respondFormErrors($validator->errors(), Controller::HTTP_BAD_REQUEST);
        }
        $name = $request->get('name');
        $email = $request->get('email');
        $password = $request->get('password');

        try {
            $userDTo = new AuthUserDTO();
            $userDTo->setName($name);
            $userDTo->setEmail($email);
            $userDTo->setPassword($password);
            $this->registerUserUseCase->execute($userDTo);
            $httpObjectDTO = new HttpObjectDTO();
            $httpObjectDTO->setItem($userDTo);

            return $this->apiResponse->responseItem($httpObjectDTO, new AuthUserTransformer());
        } catch (Throwable $throwable) {
            return $this->registerErrorResponse->handle($throwable, $email, $password);
        }
    }
}
