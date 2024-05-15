<?php

namespace App\Repositories;

use App\DTOs\TaskDTO;
use App\Exceptions\Tasks\TaskNotFoundByIdException;
use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    /**
     * @param TaskDTO $taskDTO
     * @param int $userId
     * @return Task
     */
    public function store(TaskDTO $taskDTO, int $userId): Task
    {
        return Task::create([
            'title' => $taskDTO->getTitle(),
            'description' => $taskDTO->getDescription(),
            'status' => $taskDTO->getStatus(),
            'user_id' => $userId
        ]);

    }

    /**
     * @param int $taskId
     * @return Task
     * @throws TaskNotFoundByIdException
     */
    public function findByID(int $taskId): Task
    {
        try {
            return Task::findOrFail($taskId);
        } catch (ModelNotFoundException) {
            $exception = new TaskNotFoundByIdException();
            $exception->setTaskId($taskId);
            throw $exception;
        }
    }

    /**
     * @param int $taskId
     * @param string $title
     * @param string $description
     * @param string $status
     * @return void
     * @throws TaskNotFoundByIdException
     */
    public function update(int $taskId, string $title, string $description, string $status): void
    {
        $task = $this->findByID($taskId);

        $task->title = $title;
        $task->description = $description;
        $task->status = $status;
        $task->save();

    }
}
