<?php

use NazirulAmin\SentinelActor\Tests\Stubs\ApplicationService;

use function PHPUnit\Framework\assertTrue;

uses()->group('unit');

it('can update application status', function () {
    // Create a class that uses the trait
    $service = new ApplicationService;

    // Test the trait method doesn't throw exceptions
    $service->updateApplicationStatus('running', 'Application is now running', [
        'version' => '1.0.0',
        'environment' => 'testing',
    ]);

    // If we get here without exceptions, the test passes
    assertTrue(true);
});
