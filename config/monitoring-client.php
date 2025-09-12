<?php

// config for NazirulAmin/SentinelActor
return [
    /*
    |--------------------------------------------------------------------------
    | Monitoring Client Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure the monitoring client settings.
    |
    */

    'webhook' => [
        'url' => env('MONITORING_WEBHOOK_URL'),
        'endpoint' => '/application/exceptions',
        'application_id' => env('MONITORING_APPLICATION_ID', 'app-id'),
        'secret' => env('MONITORING_WEBHOOK_SECRET'),
    ],

    'enabled' => env('MONITORING_ENABLED', true),

    'levels' => [
        'info',
        'success',
        'warning',
        'error',
        'critical',
    ],
];
