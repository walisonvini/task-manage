<?php

namespace Database\Factories;

use App\Models\Log;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

use App\Enums\TaskStatus;

class LogFactory extends Factory
{
    protected $model = Log::class;

    public function definition(): array
    {
        return [
            'action' => $this->faker->randomElement([
                'Create task ' . $this->faker->sentence(3),
                'Update task ' . $this->faker->sentence(3),
                'Delete task ' . $this->faker->sentence(3)
            ]),
            'model' => 'Task',
            'model_id' => $this->faker->numberBetween(1, 100),
            'data' => [
                'title' => $this->faker->sentence,
                'status' => $this->faker->randomElement(array_column(TaskStatus::cases(), 'value')),
                'description' => $this->faker->paragraph
            ],
            'created_at' => Carbon::now()->subDays($this->faker->numberBetween(0, 30)),
        ];
    }
}
