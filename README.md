# Sentinel Actor (Laravel Monitoring Client)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nazirul-amin/sentinel-actor.svg?style=flat-square)](https://packagist.org/packages/nazirul-amin/sentinel-actor)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/nazirul-amin/sentinel-actor/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/nazirul-amin/sentinel-actor/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/nazirul-amin/sentinel-actor/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/nazirul-amin/sentinel-actor/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/nazirul-amin/sentinel-actor.svg?style=flat-square)](https://packagist.org/packages/nazirul-amin/sentinel-actor)

This package provides a simple way to monitor your Laravel application by sending exception data to a webhook endpoint. It includes traits and utilities to automatically send exception information from jobs, notifications, and other parts of your application. Compatible with PHP 8.1+ and Laravel 10+.

# Installation

You can install the package via composer (compatible with PHP 8.1+ and Laravel 10+):

```bash
composer require nazirul-amin/sentinel-actor
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="sentinel-actor-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="sentinel-actor-config"
```

This is the contents of the published config file:

```php
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
        'critical'
    ],
];
```

## Configuration

Add the following environment variables to your `.env` file:

```env
SENTINEL_WEBHOOK_URL=https://your-monitoring-service.com/webhook
SENTINEL_APPLICATION_ID=your-app-name
SENTINEL_WEBHOOK_SECRET=your-hmac-secret
SENTINEL_ENABLED=true
```

## HMAC Signature Verification

This package supports HMAC signature verification for enhanced security. When you configure a secret in your `.env` file, all outgoing webhook requests will be signed with HMAC-SHA256.

The receiving service can verify the authenticity of the request by comparing the signature in the `Sentinel-Signature` header with a locally computed signature using the same secret.

## Usage

### Monitoring Exceptions in Jobs

To automatically send exception data from your jobs, simply use the `MonitorsExceptions` trait:

```php
<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use NazirulAmin\SentinelActor\Traits\MonitorsExceptions;
use Throwable;

class YourJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MonitorsExceptions;

    // Your job properties and methods

    public function handle()
    {
        // Your job logic here
    }

    // Optional: Add context data to be sent with exception
    protected function getMonitoringContextData(): array
    {
        return [
            'job_specific_data' => $this->someProperty,
        ];
    }
}
```

### Monitoring Exceptions in Notifications

Similarly, you can monitor exceptions in notifications:

```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NazirulAmin\SentinelActor\Traits\MonitorsExceptions;

class YourNotification extends Notification implements ShouldQueue
{
    use Queueable, MonitorsExceptions;

    // Your notification properties and methods

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Your notification logic here
    }

    // Optional: Add context data to be sent with exception
    protected function getMonitoringContextData(): array
    {
        return [
            'notification_specific_data' => $this->someProperty,
        ];
    }
}
```

### Manual Exception Monitoring

You can also manually send exception data using the facade:

```php
use NazirulAmin\SentinelActor\Facades\SentinelActor;
use Throwable;

try {
    // Some code that might throw an exception
} catch (Throwable $exception) {
    SentinelActor::sendException($exception, [
        'additional_context' => 'some_value',
    ]);
}
```

### Sending Custom Events

You can also send custom events to your monitoring service:

```php
use NazirulAmin\SentinelActor\Facades\SentinelActor;

SentinelActor::send('/application/events', [
    'application_id' => 'your-app-name',
    'event_type' => 'user_registered',
    'level' => 'info',
    'message' => 'A new user registered',
    'context' => [
        'user_id' => 123,
    ],
    'timestamp' => time(),
]);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [nazirulamin](https://github.com/nazirul-amin)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
