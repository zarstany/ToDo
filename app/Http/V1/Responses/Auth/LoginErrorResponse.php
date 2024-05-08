<?php

namespace App\Http\V1\Responses\Auth;

use App\Exceptions\Auth\EmailUsedException;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\User\UserNotFoundByEmailException;
use App\Http\V1\Controllers\Controller;
use App\Http\V1\Responses\GeneralErrorResponse;
use App\Libraries\Responders\Contracts\APIResponseInterface;
use App\Libraries\Responders\HttpErrorObjectDTO;
use Illuminate\Http\JsonResponse;
use Psr\Log\LoggerInterface;
use Throwable;

class LoginErrorResponse
{
    public function __construct(
        private readonly APIResponseInterface $apiResponse,
        private readonly GeneralErrorResponse $generalErrorResponse,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @param  array<string, string>  $items
     */
    private array $awareOf = [
        InvalidCredentialsException::class => 'invalidCredentialsException',
    ];

    /**
     * @see InvalidCredentialsException
     */
    public function handle(Throwable $throwable, string $email, string $password): JsonResponse
    {
        $className = get_class($throwable);

        if (!array_key_exists($className, $this->awareOf)) {
            $this->logger->error('v1 register error', [
                'class' => get_class($throwable),
                'email' => $email,
                'password' => $password,
                'message' => $throwable->getMessage(),
                'trace' => $throwable->getTraceAsString(),
            ]);

            return $this->generalErrorResponse->generalError();
        }

        return $this->{$this->awareOf[$className]}($throwable);
    }

    private function invalidCredentialsException(InvalidCredentialsException $invalidCredentialsException): JsonResponse
    {
        $error = new HttpErrorObjectDTO();
        $error->setTitle('Invalid Credentials')
            ->setCode('ERROR_CODE_INVALID_CREDENTIALS')
            ->setDetail('Credentials are invalid')
            ->setStatus(Controller::HTTP_BUSINESS_ERROR);

        return $this->apiResponse->respondError($error, Controller::HTTP_BUSINESS_ERROR);
    }

}
