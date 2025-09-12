<?php

namespace NazirulAmin\SentinelActor\Traits;

use NazirulAmin\SentinelActor\Facades\SentinelActor;
use Throwable;

trait MonitorsExceptions
{
    /**
     * Handle a failed job or notification.
     *
     * @return void
     */
    public function failed(Throwable $exception)
    {
        // Get class context information
        $context = $this->getMonitoringContext();

        // Send exception to monitoring service
        SentinelActor::sendException($exception, $context);
    }

    /**
     * Get context information for monitoring.
     */
    protected function getMonitoringContext(): array
    {
        $context = [];

        // Add class-specific context if method exists
        if (method_exists($this, 'getMonitoringContextData')) {
            $context = array_merge($context, $this->getMonitoringContextData());
        }

        // Add generic class information
        $context['class'] = get_class($this);

        return $context;
    }
}
