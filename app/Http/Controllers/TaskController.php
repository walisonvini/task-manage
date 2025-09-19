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

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status']);

        $tasks = $this->taskServices->all($filters);
        return $this->successResponse($tasks, 'Tasks fetched successfully');
    }

    public function show(int $id): JsonResponse
    {
        $task = $this->taskServices->show($id);
        return $this->successResponse($task, 'Task fetched successfully');
    }

    public function store(Request $request): JsonResponse
    {
        $task = $this->taskServices->create($request->all());
        return $this->successResponse($task, 'Task created successfully');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $this->taskServices->update($request->all(), $id);
        return $this->successResponse( null, 'Task updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->taskServices->delete($id);
        return $this->successResponse( null, 'Task deleted successfully');
    }
}
