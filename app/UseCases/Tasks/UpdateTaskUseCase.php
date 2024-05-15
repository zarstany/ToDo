<?php

namespace App\UseCases\Tasks;

use App\Exceptions\Tasks\TaskDoesNotBelongToUserException;
use App\Exceptions\Tasks\TaskNotFoundByIdException;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\UseCases\Contracts\Tasks\UpdateTaskUseCaseInterface;

class UpdateTaskUseCase implements UpdateTaskUseCaseInterface
{
    public function __construct(private readonly TaskRepositoryInterface $taskRepository)
    {}
    public function execute(int $taskId, string $title, string $description, string $status, $userId): void
    {
        try {
            $task = $this->taskRepository->findByID($taskId);
            if ($task->user_id !== $userId) {
                $exception = new TaskDoesNotBelongToUserException();
                $exception->setUserId($userId);
                throw $exception;
            }
            $this->taskRepository->update($taskId,$title,$description,$status);
        }
        catch (TaskNotFoundByIdException)
        {
            $exception = new TaskNotFoundByIdException();
            $exception->setTaskId($taskId);
            throw $exception;
        }
    }
}
