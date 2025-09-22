<?php

use NazirulAmin\SentinelActor\Tests\Stubs\ApplicationService;

use function PHPUnit\Framework\assertTrue;

uses()->group('unit');

it('can update health status', function () {
    // Create a class that uses the trait
    $service = new ApplicationService;

    // Test the health status method doesn't throw exceptions
    $service->updateHealthStatus(true, 'Application is healthy', [
        'version' => '1.0.0',
        'environment' => 'testing',
    ]);

    // Test with unhealthy status
    $service->updateHealthStatus(false, 'Application is unhealthy', [
        'version' => '1.0.0',
        'environment' => 'testing',
    ]);

    // If we get here without exceptions, the test passes
    assertTrue(true);
});
