<?php

namespace NazirulAmin\SentinelActor;

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
     * Send custom event data to monitoring service
     */
    public function send(string $endpoint, array $data): void
    {
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
