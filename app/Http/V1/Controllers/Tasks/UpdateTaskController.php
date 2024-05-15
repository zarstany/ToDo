<?php

namespace App\Http\V1\Controllers\Tasks;


use App\Http\V1\Controllers\Controller;
use App\Http\V1\Responses\Tasks\UpdateTaskErrorResponse;
use App\Http\V1\Transformers\TaskTransformer;
use App\Libraries\Responders\Contracts\APIResponseInterface;
use App\Libraries\Responders\HttpObjectDTO;
use App\UseCases\Contracts\Tasks\UpdateTaskUseCaseInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UpdateTaskController
{
    public function __construct(
        private readonly APIResponseInterface $apiResponse,
        private readonly UpdateTaskUseCaseInterface $updateTaskUseCase,
        private readonly  UpdateTaskErrorResponse $updateTaskErrorResponse)
    {}

    public function __invoke(Request $request, int $taskId):JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            'description' => 'required|string',
            'status' => 'sometimes|in:Pending,In progress,Completed',
        ]);

        if ($validator->fails()) {
            Log::error('Parameters invalid', ['update task' => 'parameters invalid for update task']);
            return $this->apiResponse->respondFormErrors($validator->errors(), Controller::HTTP_BAD_REQUEST);
        }
        try {
            $userId = auth()->user()->getAuthIdentifier();

            $title = $request->input('title');
            $description = $request->input('description');
            $status = $request->input('status', 'Pending');
            $this->updateTaskUseCase->execute($taskId, $title,$description,$status,$userId);
            $httpObjectDTO = new HttpObjectDTO();
            $httpObjectDTO->setStatus(Response::HTTP_ACCEPTED);
            $httpObjectDTO->setBody(['message' => 'Task updated successfully']);
            return $this->apiResponse->respond($httpObjectDTO);
        }
        catch (Throwable $throwable)
        {
            return $this->updateTaskErrorResponse->handle($throwable, $taskId, $title, $description, $status);
        }

    }
}
