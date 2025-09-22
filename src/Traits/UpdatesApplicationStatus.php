<?php

namespace NazirulAmin\SentinelActor\Traits;

use Illuminate\Support\Facades\Log;
use NazirulAmin\SentinelActor\Facades\SentinelActor;

trait UpdatesApplicationStatus
{
    /**
     * Send application health status to monitoring service
     *
     * @param  bool  $isHealthy  Whether the application is healthy/reachable
     * @param  string|null  $message  Optional message describing the health status
     * @param  array  $context  Additional context data
     */
    public function updateHealthStatus(bool $isHealthy, ?string $message = null, array $context = []): void
    {
        try {
            // Prepare health status data
            $data = [
                'application_id' => config('sentinel-actor.webhook.application_id', 'app-id'),
                'type' => 'health_status',
                'status' => $isHealthy ? 'active' : 'inactive',
                'healthy' => $isHealthy,
                'message' => $message,
                'timestamp' => time(),
                'context' => array_merge($context, $this->getStatusContext()),
            ];

            // Send health status to monitoring service
            SentinelActor::send(
                config('sentinel-actor.webhook.status_endpoint', '/application/status'),
                $data
            );
        } catch (\Throwable $e) {
            Log::error('Error in UpdatesApplicationStatus::updateHealthStatus', [
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
