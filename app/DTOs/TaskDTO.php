<?php

namespace App\DTOs;

class TaskDTO extends \League\Fractal\Resource\Item
{
    private string $title;

    private string $description;

    private string $status;

    private int $taskId;
    public function setTitle(string $title): TaskDTO
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setDescription(string $description): TaskDTO
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setStatus(string $status): TaskDTO
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
    public function setTaskId(int $taskId): TaskDTO
    {
        $this->taskId = $taskId;
        return $this;
    }

    public function getTaskId(): string
    {
        return $this->taskId;
    }
}
