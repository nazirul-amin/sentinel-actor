<?php

namespace NazirulAmin\LaravelMonitoringClient\Commands;

use Illuminate\Console\Command;
use NazirulAmin\LaravelMonitoringClient\Facades\LaravelMonitoringClient;
use NazirulAmin\LaravelMonitoringClient\Jobs\MonitoringExampleJob;

class LaravelMonitoringClientCommand extends Command
{
    public $signature = 'monitoring:test {--job : Test job monitoring} {--exception : Test exception monitoring}';

    public $description = 'Test the monitoring client functionality';

    public function handle(): int
    {
        if ($this->option('job')) {
            // Dispatch a test job that will fail
            MonitoringExampleJob::dispatch(['test' => 'data']);
            $this->info('Test job dispatched. Check your monitoring service for the exception.');

            return self::SUCCESS;
        }

        if ($this->option('exception')) {
            // Test exception monitoring
            try {
                throw new \Exception('Test exception for monitoring');
            } catch (\Exception $e) {
                LaravelMonitoringClient::sendException($e, [
                    'command' => 'monitoring:test',
                    'test_type' => 'exception',
                ]);
                $this->info('Test exception sent to monitoring service.');
            }

            return self::SUCCESS;
        }

        $this->info('Laravel Monitoring Client is installed and configured!');
        $this->line('Use --job to test job monitoring or --exception to test exception monitoring.');

        return self::SUCCESS;
    }
}
