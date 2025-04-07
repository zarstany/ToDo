<?php

namespace App\Http\V1\Controllers\Tasks;

use App\Http\V1\Controllers\Controller;
use App\Http\V1\Responses\Tasks\CreateTaskErrorResponse;
use App\Http\V1\Responses\Tasks\DeleteTaskErrorResponse;
use App\Libraries\Responders\Contracts\APIResponseInterface;
use App\Libraries\Responders\HttpObjectDTO;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;


class DeleteTaskController extends Controller
{
    public function __construct(
        private readonly APIResponseInterface     $apiResponse,
        private readonly TaskRepositoryInterface  $taskRepository,
        private readonly  DeleteTaskErrorResponse $destroyTaskErrorResponse)
    {}

    public function __invoke(Request $request)
    {
        $userId = auth()->user()->getAuthIdentifier();
        try {
            $validator = Validator::make($request->all(), [
                'task_id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                Log::error('Parameters invalid', ['Delete task' => 'parameters invalid for delete task']);
                return $this->apiResponse->respondFormErrors($validator->errors(), Controller::HTTP_BAD_REQUEST);
            }
            $taskId = $request->input('task_id');
            $this->taskRepository->deleteTask($taskId, $userId);
            $httpObject = new HttpObjectDTO();
            $httpObject->setBody(['message' => 'Task deleted successfully']);
            $httpObject->setStatus(Controller::HTTP_OK);
            return $this->apiResponse->respond($httpObject);
        }
        catch (Throwable $throwable)
        {
            Log::error('Task deleted failed', ['exception' => $throwable]);
            return $this->destroyTaskErrorResponse->handle($throwable, $userId);
        }
    }
}
