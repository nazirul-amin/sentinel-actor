<?php

namespace NazirulAmin\SentinelActor\Traits;

use Illuminate\Support\Facades\Log;
use NazirulAmin\SentinelActor\Facades\SentinelActor;

trait UpdatesApplicationStatus
{
    /**
     * Send application status update to monitoring service
     *
     * @param  string  $status  The application status (e.g., 'starting', 'running', 'maintenance', 'stopped')
     * @param  string|null  $message  Optional message describing the status
     * @param  array  $context  Additional context data
     */
    public function updateApplicationStatus(string $status, ?string $message = null, array $context = []): void
    {
        try {
            // Prepare status data
            $data = [
                'application_id' => config('sentinel-actor.webhook.application_id', 'app-id'),
                'type' => 'status_update',
                'status' => $status,
                'message' => $message,
                'timestamp' => time(),
                'context' => array_merge($context, $this->getStatusContext()),
            ];

            // Send status update to monitoring service
            SentinelActor::send(
                config('sentinel-actor.webhook.status_endpoint', '/application/status'),
                $data
            );
        } catch (\Throwable $e) {
            Log::error('Error in UpdatesApplicationStatus::updateApplicationStatus', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Get context information for status updates.
     */
    protected function getStatusContext(): array
    {
        $context = [];

        // Add class-specific context if method exists
        if (method_exists($this, 'getStatusContextData')) {
            $context = array_merge($context, $this->getStatusContextData());
        }

        // Add generic class information
        $context['class'] = get_class($this);

        return $context;
    }
}
