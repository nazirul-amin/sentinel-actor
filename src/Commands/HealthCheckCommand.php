<?php

namespace NazirulAmin\SentinelActor\Commands;

use Illuminate\Console\Command;
use NazirulAmin\SentinelActor\Facades\SentinelActor;

class HealthCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sentinel:health-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform application health check and send status to monitoring service';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            // Perform basic health checks
            $isHealthy = $this->performHealthChecks();
            
            // Send health status to monitoring service
            SentinelActor::sendHealthStatus($isHealthy, $isHealthy ? 'Application is healthy' : 'Application is unhealthy', [
                'checked_at' => now()->toISOString(),
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
            ]);

            if ($isHealthy) {
                $this->info('Health check completed successfully. Application is healthy.');
                return self::SUCCESS;
            } else {
                $this->error('Health check completed. Application is unhealthy.');
                return self::FAILURE;
            }
        } catch (\Exception $e) {
            // Send unhealthy status on error
            SentinelActor::sendHealthStatus(false, 'Health check failed: ' . $e->getMessage(), [
                'checked_at' => now()->toISOString(),
                'error' => $e->getMessage(),
            ]);
            
            $this->error('Health check failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    /**
     * Perform basic health checks.
     */
    private function performHealthChecks(): bool
    {
        // Check database connection
        try {
            \DB::connection()->getPdo();
        } catch (\Exception $e) {
            $this->error('Database connection failed: ' . $e->getMessage());
            return false;
        }

        // Check if storage is writable
        if (!is_writable(storage_path())) {
            $this->error('Storage directory is not writable');
            return false;
        }

        // Add more health checks as needed
        // For example, check cache, queue, mail, etc.

        return true;
    }
}