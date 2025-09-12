<?php

namespace NazirulAmin\SentinelActor\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use NazirulAmin\SentinelActor\Facades\SentinelActor;
use Throwable;

class MonitoringExceptionHandler extends ExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // Send exception to monitoring service
            SentinelActor::sendException($e);
        });
    }
}
