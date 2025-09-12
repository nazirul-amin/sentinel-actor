<?php

namespace NazirulAmin\SentinelActor\Facades;

use Illuminate\Support\Facades\Facade;
use Throwable;

/**
 * @method static void sendException(Throwable $exception, array $context = [])
 * @method static void send(string $endpoint, array $data)
 * @method static string echoPhrase(string $phrase)
 *
 * @see \NazirulAmin\SentinelActor\SentinelActor
 */
class SentinelActor extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \NazirulAmin\SentinelActor\SentinelActor::class;
    }
}
