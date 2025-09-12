<?php

namespace NazirulAmin\LaravelMonitoringClient\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle incoming webhook requests
     *
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request)
    {
        try {
            // Verify HMAC signature
            if (! $this->verifySignature($request)) {
                Log::warning('Invalid webhook signature', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                return response('Unauthorized', 401);
            }

            // Process the webhook data
            $payload = $request->all();

            // Validate required fields
            $requiredFields = ['application_id', 'type', 'timestamp'];
            foreach ($requiredFields as $field) {
                if (! isset($payload[$field])) {
                    Log::error("Missing required field: {$field}", ['payload' => $payload]);

                    return response('Bad Request', 400);
                }
            }

            // Process based on type
            switch ($payload['type']) {
                case 'exception':
                    $this->processException($payload);
                    break;

                default:
                    Log::info("Received webhook with unknown type: {$payload['type']}", ['payload' => $payload]);
                    break;
            }

            return response('OK', 200);
        } catch (Exception $e) {
            Log::error('Error processing webhook', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all(),
            ]);

            return response('Internal Server Error', 500);
        }
    }

    /**
     * Verify HMAC signature of the request
     */
    protected function verifySignature(Request $request): bool
    {
        $secret = config('monitoring-client.webhook.secret');

        // If no secret is configured, skip verification
        if (empty($secret)) {
            return true;
        }

        $signature = $request->header('X-Signature');
        $timestamp = $request->header('X-Timestamp');

        if (empty($signature) || empty($timestamp)) {
            return false;
        }

        // Recreate the payload with the timestamp from header
        $payload = $request->all();
        $payload['timestamp'] = $timestamp;

        // Sort data to ensure consistent signature generation
        ksort($payload);

        // Create JSON payload
        $jsonPayload = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // Generate expected signature
        $expectedSignature = hash_hmac('sha256', $jsonPayload, $secret);

        // Use hash_equals to prevent timing attacks
        return hash_equals($signature, $expectedSignature);
    }

    /**
     * Process exception data
     */
    protected function processException(array $data): void
    {
        // Here you would typically store the exception data in your database
        // For now, we'll just log it

        Log::info('Exception received via webhook', [
            'application_id' => $data['application_id'],
            'type' => $data['type'],
            'message' => $data['message'] ?? 'No message',
            'file' => $data['file'] ?? 'Unknown',
            'line' => $data['line'] ?? 0,
            'timestamp' => $data['timestamp'],
            'code' => $data['code'] ?? 0,
            'context' => $data['context'] ?? [],
        ]);

        // You could also store this in a database table
        // MonitoringEvent::create($data);
    }
}
