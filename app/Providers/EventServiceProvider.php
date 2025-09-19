<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\Tasks\TaskCreated::class => [
            \App\Listeners\Tasks\LogTaskCreated::class,
        ],
        \App\Events\Tasks\TaskUpdated::class => [
            \App\Listeners\Tasks\LogTaskUpdated::class,
        ],
        \App\Events\Tasks\TaskDeleted::class => [
            \App\Listeners\Tasks\LogTaskDeleted::class,
        ],
    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
