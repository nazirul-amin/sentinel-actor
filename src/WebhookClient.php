<?php

namespace NazirulAmin\SentinelActor;

use Illuminate\Support\Facades\App;
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
        if (! config('sentinel-actor.enabled', true)) {
            return;
        }

        $webhookUrl = config('sentinel-actor.webhook.url');

        if (empty($webhookUrl)) {
            Log::warning('Monitoring webhook URL not configured');

            return;
        }

        try {
            // Add timestamp to data for HMAC calculation
            $data['timestamp'] = $data['timestamp'] ?? time();

            // Create JSON payload
            $payload = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            // Generate HMAC signature
            $secret = config('sentinel-actor.webhook.secret');
            $signature = hash_hmac('sha256', $payload, $secret);

            // Send request with HMAC signature in header
            $response = Http::withHeaders([
                'Sentinel-Signature' => $signature,
            ])->withBody($payload, 'application/json')->post($webhookUrl.$endpoint);

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
     * Send exception data
     */
    public function sendException(Throwable $exception, array $context = []): void
    {
        try {
            $data = [
                'application_id' => config('sentinel-actor.webhook.application_id', 'app-id'),
                'environment' => config('sentinel-actor.webhook.environment', 'production'),
                'type' => 'exception',
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'timestamp' => time(),
                'code' => $exception->getCode(),
                'trace' => $exception->getTrace(),
                'context' => $context,
            ];

            $this->send(config('sentinel-actor.webhook.endpoint', '/application/exceptions'), $data);
        } catch (Throwable $e) {
            Log::error('Error in WebhookClient::sendException', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Send application status update
     */
    public function sendStatusUpdate(string $status, ?string $message = null, array $context = []): void
    {
        try {
            $data = [
                'application_id' => config('sentinel-actor.webhook.application_id', 'app-id'),
                'environment' => App::environment(),
                'type' => 'status_update',
                'status' => $status,
                'message' => $message,
                'timestamp' => time(),
                'context' => $context,
            ];

            $this->send(
                config('sentinel-actor.webhook.status_endpoint', '/application/status'),
                $data
            );
        } catch (Throwable $e) {
            Log::error('Error in WebhookClient::sendStatusUpdate', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
