<?php

namespace App\Listeners\Tasks;

use App\Events\Tasks\TaskDeleted;
use App\Services\LogService;
use Carbon\Carbon;

class LogTaskDeleted
{
    public function __construct(
        private LogService $logService
    ) {}

    public function handle(TaskDeleted $event): void
    {
        $this->logService->create([
            'action' => 'Delete task ' . $event->task->title,
            'model' => 'Task',
            'model_id' => $event->task->id,
            'created_at' => Carbon::now(),
        ]);
    }
}
