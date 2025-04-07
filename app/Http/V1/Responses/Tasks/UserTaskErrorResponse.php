<?php


namespace App\Http\V1\Responses\Tasks;

use App\Exceptions\Tasks\NoTasksForUserException;
use App\Exceptions\Tasks\TaskNotFoundByIdException;
use App\Http\V1\Controllers\Controller;
use App\Http\V1\Responses\GeneralErrorResponse;
use App\Libraries\Responders\Contracts\APIResponseInterface;
use App\Libraries\Responders\HttpErrorObjectDTO;
use Illuminate\Http\JsonResponse;
use Psr\Log\LoggerInterface;
use Throwable;

class UserTaskErrorResponse
{
    public function __construct(
        private readonly APIResponseInterface $apiResponse,
        private readonly GeneralErrorResponse $generalErrorResponse,
        private readonly LoggerInterface      $logger
    )
    {
    }

    /**
     * @param array<string, string> $items
     */
    private array $awareOf = [
        NoTasksForUserException::class => 'noTasksForUserException'
    ];

    /**
     * @see TaskNotFoundByIdException
     */
    public function handle(Throwable $throwable, int $taskId): JsonResponse
    {
        $className = get_class($throwable);

        if (!array_key_exists($className, $this->awareOf)) {
            $this->logger->error('v1 delete task error', [
                'exception' => get_class($throwable),
                'task_id' => $taskId,
                'message' => $throwable->getMessage(),
            ]);

            return $this->generalErrorResponse->generalError();
        }

        return $this->{$this->awareOf[$className]}($throwable);
    }

    private function noTasksForUserException(NoTasksForUserException $noTasksForUserException): JsonResponse
    {
        $this->logger->warning('v1 No task warning: user has no tasks', [
            'user_id' => $noTasksForUserException->getUserId(),
        ]);
        $error = new HttpErrorObjectDTO();
        $error->setTitle('No Tasks Found for User')
            ->setCode('USER_HAS_NO_TASKS')
            ->setDetail('The user does not have any tasks assigned.')
            ->setStatus(Controller::HTTP_BUSINESS_ERROR);
        return $this->apiResponse->respondError($error, Controller::HTTP_BUSINESS_ERROR);
    }

}
