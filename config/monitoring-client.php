<?php

// config for NazirulAmin/LaravelMonitoringClient
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
        'application_id' => env('MONITORING_APPLICATION_ID', 'laravel-app'),
        'secret' => env('MONITORING_WEBHOOK_SECRET'), // HMAC secret for request verification
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
