<?php

uses()->group('unit');

it('includes application version in config', function () {
    $version = config('sentinel-actor.webhook.application_version');

    // Check that the version is set in the config
    expect($version)->toBeString();
    expect(strlen($version))->toBeGreaterThan(0);

    // Default should be 1.0.0
    expect($version)->toBe('1.0.0');
});
