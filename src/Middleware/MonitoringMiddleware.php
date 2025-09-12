<?php

namespace NazirulAmin\SentinelActor\Middleware;

use Closure;
use Illuminate\Http\Request;

class MonitoringMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return void
     */
    public function terminate($request, $response)
    {
        // This method is called after the response is sent
        // You can add monitoring logic here if needed
    }
}
