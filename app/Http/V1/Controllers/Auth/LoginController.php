<?php

namespace App\Http\V1\Controllers\Auth;

use App\Http\V1\Controllers\Controller;
use App\Http\V1\Responses\Auth\LoginErrorResponse;
use App\Http\V1\Transformers\AuthUserTransformer;
use App\Libraries\Auth\AuthUserDTO;
use App\Libraries\Responders\Contracts\APIResponseInterface;
use App\Libraries\Responders\HttpObjectDTO;
use App\UseCases\Contracts\Users\LoginUseCaseInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class LoginController
{
    public function __construct(
        private readonly LoginUseCaseInterface $loginUseCase,
        private readonly APIResponseInterface $apiResponse,
        private readonly LoginErrorResponse $loginErrorResponse
    ){
    }

    public function __invoke(Request $request):JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            Log::error('Parameters invalid', ['Login' => 'parameters invalid for login']);
            return $this->apiResponse->respondFormErrors($validator->errors(), Controller::HTTP_BAD_REQUEST);
        }
        $email = $request->get('email');
        $password = $request->get('password');

        try {
            $userDTo = new AuthUserDTO();
            $userDTo->setEmail($email);
            $userDTo->setPassword($password);
            $this->loginUseCase->execute($userDTo);
            $httpObjectDTO = new HttpObjectDTO();
            $httpObjectDTO->setItem($userDTo);
            return $this->apiResponse->responseItem($httpObjectDTO,new AuthUserTransformer());
        }
        catch (Throwable $throwable) {
            return $this->loginErrorResponse->handle($throwable, $email, $password, );
        }
    }

}
