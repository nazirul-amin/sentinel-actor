<?php

namespace NazirulAmin\SentinelActor\Tests\Stubs;

use NazirulAmin\SentinelActor\Traits\UpdatesApplicationStatus;

class ApplicationService
{
    use UpdatesApplicationStatus;

    protected function getStatusContext(): array
    {
        return [
            'service' => 'test-service',
        ];
    }
}
