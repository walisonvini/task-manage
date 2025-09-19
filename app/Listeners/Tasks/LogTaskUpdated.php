<?php

namespace App\Listeners\Tasks;

use App\Events\Tasks\TaskUpdated;
use App\Services\LogService;
use Carbon\Carbon;

class LogTaskUpdated
{
    public function __construct(
        private LogService $logService
    ) {}

    public function handle(TaskUpdated $event): void
    {
        $this->logService->create([
            'action' => 'Update task ' . $event->task->title,
            'model' => 'Task',
            'model_id' => $event->task->id,
            'data' => [
                'old' => $event->oldData,
                'new' => $event->newData,
            ],
            'created_at' => Carbon::now(),
        ]);
    }
}
