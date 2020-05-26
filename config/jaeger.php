<?php
declare(strict_types=1);

return [
    'enabled'       => env('JAEGER_ENABLED', true),
    'server_name'   => env('APP_NAME', 'myparcelcom-microservice'),
    'agent_host'    => env('JAEGER_AGENT_HOST', 'jaeger'),
    'agent_port'    => env('JAEGER_AGENT_PORT', '6831'),

    /*
    |--------------------------------------------------------------------------
    | Routes to trace (whitelist)
    |--------------------------------------------------------------------------
    |
    | This is a list of route aliases that are enabled for tracing. No other
    | route will be traced
    */
    'trace_routes'  => [
        'create-shipment',
    ],
];
