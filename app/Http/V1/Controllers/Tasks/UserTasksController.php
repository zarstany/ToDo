<?php
namespace App\Http\V1\Controllers\Tasks;

use App\DTOs\TaskDTO;
use App\Exceptions\Tasks\NoTasksForUserException;
use App\Http\V1\Responses\Tasks\DeleteTaskErrorResponse;
use App\Http\V1\Responses\Tasks\UserTaskErrorResponse;
use App\Http\V1\Transformers\TaskTransformer;
use App\Libraries\Responders\Contracts\APIResponseInterface;
use App\Libraries\Responders\HttpObjectDTO;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Throwable;
class UserTasksController
{
    public function __construct(
        private readonly APIResponseInterface $apiResponse,
        private readonly TaskRepositoryInterface $taskRepository,
        private readonly  UserTaskErrorResponse $userTaskErrorResponse)
    {}

    public function __invoke()
    {
        $userId = auth()->user()->getAuthIdentifier();
        try {
            $tasks = $this->taskRepository->allTasksByUserId($userId);
            if ($tasks->isEmpty()) {
                $exception = new NoTasksForUserException();
                $exception->setUserId($userId);
                Throw $exception;
            }
            $taskDTOs = $tasks->map(function ($task) {
                $taskDTO = new TaskDTO();
                $taskDTO->setTaskId($task->id);
                $taskDTO->setTitle($task->title);
                $taskDTO->setDescription($task->description);
                $taskDTO->setStatus($task->status);
                return $taskDTO;
            });
            $httpObject = new HttpObjectDTO();
            $httpObject->setCollection($taskDTOs);
            return $this->apiResponse->respondCollection($httpObject, new TaskTransformer());

        } catch (Throwable $throwable)
        {
            return $this->userTaskErrorResponse->handle($throwable, $userId);
        }
    }
}
