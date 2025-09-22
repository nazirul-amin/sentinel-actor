<?php

return [
    'webhook' => [
        'url' => env('SENTINEL_WEBHOOK_URL'),
        'endpoint' => env('SENTINEL_EXCEPTION_URL', '/application/exceptions'),
        'status_endpoint' => env('SENTINEL_STATUS_URL', '/application/status'),
        'application_id' => env('SENTINEL_APPLICATION_ID', 'app-id'),
        'application_version' => env('SENTINEL_APPLICATION_VERSION', '1.0.0'),
        'secret' => env('SENTINEL_WEBHOOK_SECRET'),
    ],

    'enabled' => env('SENTINEL_ENABLED', true),

    'levels' => [
        'info',
        'success',
        'warning',
        'error',
        'critical',
    ],
];
