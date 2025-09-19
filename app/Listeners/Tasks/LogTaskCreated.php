<?php

namespace App\Listeners\Tasks;

use App\Events\Tasks\TaskCreated;
use App\Services\LogService;
use Carbon\Carbon;

class LogTaskCreated
{
    public function __construct(
        private LogService $logService
    ) {}

    public function handle(TaskCreated $event): void
    {
        $this->logService->create([
            'action' => 'Create task ' . $event->task->title,
            'model' => 'Task',
            'model_id' => $event->task->id,
            'data' => $event->data,
            'created_at' => Carbon::now(),
        ]);
    }
}
