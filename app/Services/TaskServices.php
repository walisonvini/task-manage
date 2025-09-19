<?php

namespace App\Services;

use App\Models\Task;
use App\Enums\TaskStatus;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;

use App\Events\Tasks\TaskCreated;
use App\Events\Tasks\TaskUpdated;
use App\Events\Tasks\TaskDeleted;

class TaskServices
{

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

        event(new TaskCreated($task, $data));

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
        $oldData = $task->toArray();

        $result = $task->update($data);

        event(new TaskUpdated($task, $oldData, $data));

        return $result;
    }

    public function delete(int $id): Task | bool
    {
        $task = Task::findOrFail($id);

        $result = $task->delete();

        event(new TaskDeleted($task));

        return $result;
    }
}