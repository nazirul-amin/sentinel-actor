<?php

namespace NazirulAmin\SentinelActor\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \NazirulAmin\SentinelActor\SentinelActor
 */
class SentinelActor extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \NazirulAmin\SentinelActor\SentinelActor::class;
    }
}
