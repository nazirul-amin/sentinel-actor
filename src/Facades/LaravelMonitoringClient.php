<?php

namespace NazirulAmin\LaravelMonitoringClient\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \NazirulAmin\LaravelMonitoringClient\LaravelMonitoringClient
 */
class LaravelMonitoringClient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \NazirulAmin\LaravelMonitoringClient\LaravelMonitoringClient::class;
    }
}
