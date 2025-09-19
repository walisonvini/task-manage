<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\TaskStatus;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
    	return [
    	    'title' => $this->faker->sentence,
    	    'description' => $this->faker->paragraph,
    	    'status' => $this->faker->randomElement(array_column(TaskStatus::cases(), 'value')),
    	];
    }
}
