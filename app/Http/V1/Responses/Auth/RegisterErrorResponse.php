<?php

namespace App\Http\V1\Responses\Auth;

use App\Exceptions\Auth\EmailUsedException;
use App\Exceptions\User\UserNotFoundByEmailException;
use App\Http\V1\Controllers\Controller;
use App\Http\V1\Responses\GeneralErrorResponse;
use App\Libraries\Responders\Contracts\APIResponseInterface;
use App\Libraries\Responders\HttpErrorObjectDTO;
use Illuminate\Http\JsonResponse;
use Psr\Log\LoggerInterface;
use Throwable;

class RegisterErrorResponse
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
        EmailUsedException::class => 'emailUsedException',
        UserNotFoundByEmailException::class => 'userNotFoundByEmailException',
    ];

    /**
     * @see emailUsedException
     */
    public function handle(Throwable $throwable, string $email, string $password): JsonResponse
    {
        $className = get_class($throwable);

        if (! array_key_exists($className, $this->awareOf)) {
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

    private function emailUsedException(EmailUsedException $emailUsedException): JsonResponse
    {
        $this->logger->warning('v1 register warning email used', [
            'email' => $emailUsedException->getEmail(),
        ]);

        $error = new HttpErrorObjectDTO();
        $error->setTitle('Email used')
            ->setCode('ERROR_CODE_REGISTER_EMAIL_USED')
            ->setDetail('The email has already been registered.')
            ->setStatus(Controller::HTTP_BUSINESS_ERROR)
            ->setSource([
                'email' => $emailUsedException->getEmail(),
            ]);

        return $this->apiResponse->respondError($error, Controller::HTTP_BUSINESS_ERROR);
    }
}
