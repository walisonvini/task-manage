<?php

namespace App\Services;

use App\Models\Task;
use App\Enums\TaskStatus;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;

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

    public function create(array $data)
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'status' => 'required|string|in:' . implode(',', array_column(TaskStatus::cases(), 'value')),
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return Task::create($data);
    }

    public function update(array $data, int $id)
    {
        $validator = Validator::make($data, [
            'title' => 'sometimes|string|max:255',
            'status' => 'sometimes|string|in:' . implode(',', array_column(TaskStatus::cases(), 'value')),
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return Task::findOrFail($id)->update($data);
    }

    public function delete(int $id)
    {
        return Task::findOrFail($id)->delete();
    }
}