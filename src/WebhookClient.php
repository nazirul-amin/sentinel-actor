<?php

namespace NazirulAmin\SentinelActor;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class WebhookClient
{
    /**
     * Send exception data to the monitoring service
     */
    public function send(string $endpoint, array $data): void
    {
        if (! config('monitoring-client.enabled', true)) {
            return;
        }

        $webhookUrl = config('monitoring-client.webhook.url');

        if (empty($webhookUrl)) {
            Log::warning('Monitoring webhook URL not configured');

            return;
        }

        try {
            // Add timestamp to data for HMAC calculation
            $data['timestamp'] = $data['timestamp'] ?? time();

            // Generate HMAC signature
            $secret = config('monitoring-client.webhook.secret');
            $signature = $this->generateSignature($data, $secret);

            // Send request with HMAC signature in header
            $response = Http::withHeaders([
                'Monitoring-Signature' => $signature,
            ])->post($webhookUrl.$endpoint, $data);

            if (! $response->successful()) {
                Log::error('Failed to send monitoring data', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (Throwable $throwable) {
            Log::error('Exception while sending monitoring data', [
                'message' => $throwable->getMessage(),
                'trace' => $throwable->getTraceAsString(),
            ]);
        }
    }

    /**
     * Generate HMAC signature for the data
     */
    protected function generateSignature(array $data, ?string $secret): string
    {
        if (empty($secret)) {
            return '';
        }

        // Sort data to ensure consistent signature generation
        ksort($data);

        // Create JSON payload
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // Generate HMAC signature
        return hash_hmac('sha256', $payload, $secret);
    }

    /**
     * Send exception data
     */
    public function sendException(Throwable $exception, array $context = []): void
    {
        $data = [
            'application_id' => config('monitoring-client.webhook.application_id', 'app-id'),
            'type' => 'exception',
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'timestamp' => time(),
            'code' => $exception->getCode(),
            'trace' => $exception->getTrace(),
            'context' => $context,
        ];

        $this->send(config('monitoring-client.webhook.endpoint', '/application/exceptions'), $data);
    }
}
