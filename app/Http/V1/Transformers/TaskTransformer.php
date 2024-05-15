<?php

namespace App\Http\V1\Transformers;

use App\DTOs\TaskDTO;

class TaskTransformer extends TransformerAbstract
{
    protected string $resource = 'auth.user-token';

    /**
     * @return array<string, int|string> $items
     */
    public function transform(TaskDTO $taskDTO): array
    {
        return [
            'title' => $taskDTO->getTitle(),
            'description' => $taskDTO->getDescription(),
            'status' => $taskDTO->getStatus(),
        ];
    }
}
