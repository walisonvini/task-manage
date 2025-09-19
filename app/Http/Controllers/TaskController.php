<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use Illuminate\Validation\ValidationException;

use App\Models\Task;

use App\Services\TaskServices;

use App\Traits\ApiResponse;

class TaskController extends Controller
{
    use ApiResponse;

    public function __construct(
        private TaskServices $taskServices
    ) {}

    public function store(Request $request): JsonResponse
    {
        try {
            $task = $this->taskServices->create($request->all());
            return $this->successResponse($task, 'Task created successfully');
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Error creating task', 500);
        }
    }
}
