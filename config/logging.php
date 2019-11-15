<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Protected attributes
    |--------------------------------------------------------------------------
    |
    | All attributes in this array will be stripped from the logging context
    | before being logged. This is done so we're not storing sensitive
    | user data anywhere.
    */
    'protected_attributes' => [
        'request.headers.X-MYPARCELCOM-CREDENTIALS',
        'request.headers.X-MYPARCELCOM-SECRET',
    ],

    'default' => env('APP_LOG', 'stack'),

    'log_key' => env('APP_LOG_KEY', 'log'),

    'channels' => [
        'stack' => [
            'driver'   => 'stack',
            'channels' => ['stdout', 'rollbar'],
        ],

        'stdout' => [
            'driver'  => 'monolog',
            'handler' => Monolog\Handler\StreamHandler::class,
            'with'    => [
                'stream' => 'php://stdout',
                'level'  => Monolog\Logger::DEBUG,
            ],
        ],

        'rollbar' => [
            'driver' => 'monolog',
            'handler' => Rollbar\Laravel\MonologHandler::class,
            'access_token' => env('ROLLBAR_TOKEN'),
            'level' => 'debug',
        ],

        'null' => [
            'driver'  => 'monolog',
            'handler' => Monolog\Handler\NullHandler::class,
        ],

        'redis' => [
            'driver' => 'redis',
        ],

        'single' => [
            'driver' => 'single',
            'path'   => storage_path('logs/laravel.log'),
        ],
    ],
];
