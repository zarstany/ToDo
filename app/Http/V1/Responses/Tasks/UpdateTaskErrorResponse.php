<?php

namespace App\Http\V1\Responses\Tasks;

use App\Exceptions\Tasks\TaskDoesNotBelongToUserException;
use App\Exceptions\Tasks\TaskNotFoundByIdException;
use App\Exceptions\User\UserNotFoundByIDException;
use App\Http\V1\Controllers\Controller;
use App\Http\V1\Responses\GeneralErrorResponse;
use App\Libraries\Responders\Contracts\APIResponseInterface;
use App\Libraries\Responders\HttpErrorObjectDTO;
use Illuminate\Http\JsonResponse;
use Psr\Log\LoggerInterface;
use Throwable;

class UpdateTaskErrorResponse
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
        TaskNotFoundByIdException::class => 'taskNotFoundByIdException',
        TaskDoesNotBelongToUserException::class => 'taskDoesNotBelongToUserException'
    ];

    /**
     * @see TaskNotFoundByIdException
     */
    public function handle(Throwable $throwable, int $taskId, string $title, string $description, string $status): JsonResponse
    {
        $className = get_class($throwable);

        if (! array_key_exists($className, $this->awareOf)) {
            $this->logger->error('v1 update task error', [
                'exception' => get_class($throwable),
                'task_id' => $taskId,
                'title' => $title,
                'description' => $description,
                'status' => $status,
                'message' => $throwable->getMessage(),
            ]);

            return $this->generalErrorResponse->generalError();
        }

        return $this->{$this->awareOf[$className]}($throwable);
    }

    private function taskNotFoundByIdException(TaskNotFoundByIdException $taskNotFoundByIdException): JsonResponse
    {
        $this->logger->warning('v1 task delete warning task not found', [
            'task_id' => $taskNotFoundByIdException->getTaskId(),
        ]);
        $error = new HttpErrorObjectDTO();
        $error->setTitle('Task not found')
            ->setCode('TASK_NOT_FOUND_BY_ID')
            ->setDetail('task not found by task id')
            ->setStatus(Controller::HTTP_BUSINESS_ERROR);

        return $this->apiResponse->respondError($error, Controller::HTTP_BUSINESS_ERROR);
    }
    private function TaskDoesNotBelongToUserException(TaskDoesNotBelongToUserException $taskDoesNotBelongToUserException): JsonResponse
    {
        $this->logger->warning('User is not the owner of the task', [
            'task_id' => $taskDoesNotBelongToUserException->getUserId(),
        ]);
        $error = new HttpErrorObjectDTO();
        $error->setTitle('Task does not belong to user')
            ->setCode('TASK_NOT_BELONG_TO_USER')
            ->setDetail('The requested task does not belong to the current user')
            ->setStatus(Controller::HTTP_BUSINESS_ERROR);

        return $this->apiResponse->respondError($error, Controller::HTTP_BUSINESS_ERROR);
    }

}
