<?php

namespace NazirulAmin\LaravelMonitoringClient\Commands;

use Illuminate\Console\Command;

class LaravelMonitoringClientCommand extends Command
{
    public $signature = 'laravel-monitoring-client';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
