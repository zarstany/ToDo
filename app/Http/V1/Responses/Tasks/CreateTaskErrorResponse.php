<?php

namespace App\Http\V1\Responses\Tasks;


use App\Exceptions\User\UserNotFoundByIDException;
use App\Http\V1\Controllers\Controller;
use App\Http\V1\Responses\GeneralErrorResponse;
use App\Libraries\Responders\Contracts\APIResponseInterface;
use App\Libraries\Responders\HttpErrorObjectDTO;
use Illuminate\Http\JsonResponse;
use Psr\Log\LoggerInterface;
use Throwable;

class CreateTaskErrorResponse
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
        UserNotFoundByIDException::class => 'userNotFoundByIDException',
    ];

    /**
     * @see UserNotFoundByIDException
     */
    public function handle(Throwable $throwable, string $userId): JsonResponse
    {
        $className = get_class($throwable);

        if (! array_key_exists($className, $this->awareOf)) {
            $this->logger->error('v1 create task error', [
                'class' => get_class($throwable),
                'email' => $userId,
                'message' => $throwable->getMessage(),
                'trace' => $throwable->getTraceAsString(),
            ]);

            return $this->generalErrorResponse->generalError();
        }

        return $this->{$this->awareOf[$className]}($throwable);
    }

    private function invalidCredentialsException(UserNotFoundByIDException $userNotFoundByIDException): JsonResponse
    {
        $error = new HttpErrorObjectDTO();
        $error->setTitle('UserNotFound')
            ->setCode('USER_NOT_FOUND_BY_ID')
            ->setDetail('user not found by user id')
            ->setStatus(Controller::HTTP_BUSINESS_ERROR);

        return $this->apiResponse->respondError($error, Controller::HTTP_BUSINESS_ERROR);
    }

}
