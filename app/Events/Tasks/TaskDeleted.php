<?php

namespace App\Events\Tasks;

use App\Models\Task;
use Illuminate\Queue\SerializesModels;

class TaskDeleted
{
    use SerializesModels;

    public function __construct(
        public Task $task
    ) {}
}
