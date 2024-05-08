<?php

namespace App\Http\V1\Responses;

use App\Exceptions\Users\UserNotFoundByEmailException;
use App\Exceptions\Users\UserNotFoundByIDException;
use App\Http\V1\Controllers\Controller;
use App\Libraries\Responders\Contracts\APIResponseInterface;
use App\Libraries\Responders\HttpErrorObjectDTO;
use Illuminate\Http\JsonResponse;

class GeneralErrorResponse
{
    public const TITLE_ERROR = 'General error';

    public const GENERAL_ERROR = 'GENERAL_ERROR';

    public function __construct(
        private readonly APIResponseInterface $apiResponse,
    ) {
    }

    public function userNotFoundException(UserNotFoundByEmailException|UserNotFoundByIDException $_): JsonResponse
    {
        $error = new HttpErrorObjectDTO();
        $error->setTitle('User Not Found')
            ->setCode('ERROR_CODE_USERS_NOT_FOUND')
            ->setDetail('The user do not exist.')
            ->setStatus(Controller::HTTP_BUSINESS_ERROR);

        return $this->apiResponse->respondError($error, Controller::HTTP_BUSINESS_ERROR);
    }

    public function generalError(): JsonResponse
    {
        $error = new HttpErrorObjectDTO();
        $error->setTitle(self::TITLE_ERROR)
            ->setCode(self::GENERAL_ERROR)
            ->setDetail('An error occurred, please try again.')
            ->setStatus(Controller::HTTP_INTERNAL_SERVER_ERROR);

        return $this->apiResponse->respondError($error, Controller::HTTP_INTERNAL_SERVER_ERROR);
    }
}
