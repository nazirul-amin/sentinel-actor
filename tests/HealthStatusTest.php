<?php

use NazirulAmin\SentinelActor\SentinelActor;

use function PHPUnit\Framework\assertTrue;

uses()->group('unit');

it('can send health status', function () {
    // Create a SentinelActor instance
    $sentinel = new SentinelActor;

    // Test sending healthy status
    $sentinel->sendHealthStatus(true, 'Application is healthy');

    // Test sending unhealthy status
    $sentinel->sendHealthStatus(false, 'Application is unhealthy');

    // If we get here without exceptions, the test passes
    assertTrue(true);
});
