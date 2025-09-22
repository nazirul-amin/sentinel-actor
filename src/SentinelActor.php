<?php

namespace NazirulAmin\SentinelActor;

use Illuminate\Support\Facades\App;
use Throwable;

class SentinelActor
{
    protected WebhookClient $webhookClient;

    public function __construct()
    {
        $this->webhookClient = new WebhookClient;
    }

    /**
     * Send exception data to monitoring service
     */
    public function sendException(Throwable $exception, array $context = []): void
    {
        $this->webhookClient->sendException($exception, $context);
    }

    /**
     * Send application status update to monitoring service
     */
    public function sendStatusUpdate(string $status, ?string $message = null, array $context = []): void
    {
        $this->webhookClient->sendStatusUpdate($status, $message, $context);
    }

    /**
     * Send custom event data to monitoring service
     */
    public function send(string $endpoint, array $data): void
    {
        // Ensure environment is included in custom events
        $data['environment'] = $data['environment'] ?? App::environment();

        $this->webhookClient->send($endpoint, $data);
    }

    /**
     * Echo a phrase - example method from the original package
     */
    public function echoPhrase(string $phrase): string
    {
        return $phrase;
    }
}
