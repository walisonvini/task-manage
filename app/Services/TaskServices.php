<?php

namespace App\Services;

use App\Models\Task;
use App\Enums\TaskStatus;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

use App\Services\LogService;

class TaskServices
{
    public function __construct(
        private LogService $logService
    ) {}

    public function all(array $filters = []) : Collection
    {
        return Task::filter($filters)->get();
    }

    public function show(int $id) : Task
    {
       return Task::findOrFail($id);
    }

    public function create(array $data): Task
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'status' => 'required|string|in:' . implode(',', array_column(TaskStatus::cases(), 'value')),
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $task = Task::create($data);

        $this->logService->create([
            'action' => 'Create task ' . $task->title,
            'model' => 'Task',
            'model_id' => $task->id,
            'data' => $data,
            'created_at' => Carbon::now(),
        ]);

        return $task;
    }

    public function update(array $data, int $id): Task | bool
    {
        $validator = Validator::make($data, [
            'title' => 'sometimes|string|max:255',
            'status' => 'sometimes|string|in:' . implode(',', array_column(TaskStatus::cases(), 'value')),
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $task = Task::findOrFail($id);

        $this->logService->create([
            'action' => 'Update task ' . $task->title,
            'model' => 'Task',
            'model_id' => $task->id,
            'data' => [
                'old' => $task->toArray(),
                'new' => $data,
            ],
            'created_at' => Carbon::now(),
        ]);

        return $task->update($data);
    }

    public function delete(int $id): Task | bool
    {
        $task = Task::findOrFail($id);

        $this->logService->create([
            'action' => 'Delete task ' . $task->title,
            'model' => 'Task',
            'model_id' => $task->id,
            'created_at' => Carbon::now(),
        ]);

        return $task->delete();
    }
}