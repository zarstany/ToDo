<?php

namespace App\Http\V1\Controllers\Tasks;

use App\DTOs\TaskDTO;
use App\Http\V1\Controllers\Controller;
use App\Http\V1\Responses\Tasks\CreateTaskErrorResponse;
use App\Http\V1\Transformers\TaskTransformer;
use App\Libraries\Responders\Contracts\APIResponseInterface;
use App\Libraries\Responders\HttpObjectDTO;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class CreateTaskController
{
    public function __construct(
    private readonly APIResponseInterface $apiResponse,
    private readonly TaskRepositoryInterface $taskRepository,
    private readonly  CreateTaskErrorResponse $createTaskErrorResponse)
    {}

    public function __invoke(Request $request):JsonResponse
    {
        try {

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:50',
                'description' => 'required|string',
                'status' => 'sometimes|in:Pending,In progress,Completed',
            ]);

            if ($validator->fails()) {
                Log::error('Parameters invalid', ['Create task' => 'parameters invalid for create task']);
                return $this->apiResponse->respondFormErrors($validator->errors(), Controller::HTTP_BAD_REQUEST);
            }
            $userId = auth()->user()->getAuthIdentifier();
            $title = $request->input('title');
            $description = $request->input('description');
            $status = $request->input('status', 'Pending');

            $taskDTO = new TaskDTO();
            $taskDTO->setTitle($title);
            $taskDTO->setDescription($description);
            $taskDTO->setStatus($status);
            $this->taskRepository->store($taskDTO, $userId);
            $httpObjectDTO = new HttpObjectDTO();
            $httpObjectDTO->setItem($taskDTO);

            return $this->apiResponse->responseItem($httpObjectDTO, new TaskTransformer());
        }
        catch (Throwable $throwable)
        {
            return $this->createTaskErrorResponse->handle($throwable, $userId);

        }

    }
}
