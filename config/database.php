<?php

return [

    'default' => env('DB_CONNECTION', 'mysql'),

    'migrations' => 'migrations',

    'connections' => [

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'mysql'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'task_manager'),
            'username' => env('DB_USERNAME', 'task_manager'),
            'password' => env('DB_PASSWORD', 'secret'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'mongodb' => [
            'driver'   => 'mongodb',
            'host'     => env('MONGO_HOST', 'mongo'),
            'port'     => env('MONGO_PORT', 27017),
            'database' => env('MONGO_DB', 'task_manager_logs'),
            'username' => env('MONGO_USER', ''),
            'password' => env('MONGO_PASSWORD', ''),
        ],

    ],

];
