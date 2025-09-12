<?php

return [
    'webhook' => [
        'url' => env('SENTINEL_WEBHOOK_URL'),
        'endpoint' => '/application/exceptions',
        'application_id' => env('SENTINEL_APPLICATION_ID', 'app-id'),
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
