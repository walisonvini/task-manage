<?php

namespace App\Services;

use App\Models\Task;
use App\Enums\TaskStatus;
use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\ValidationException;

class TaskServices
{
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
}