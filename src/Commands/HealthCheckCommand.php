<?php

namespace NazirulAmin\SentinelActor\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use NazirulAmin\SentinelActor\Facades\SentinelActor;
use Throwable;

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
    protected $description = 'Perform application health checks and send status to the monitoring service';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $results = $this->performHealthChecks();
            $isHealthy = collect($results)->every(fn ($result) => $result['status'] === true);

            SentinelActor::sendHealthStatus(
                $isHealthy,
                $isHealthy ? 'Application is healthy' : 'Application has failing checks',
                array_merge([
                    'checked_at' => now()->toISOString(),
                    'php_version' => PHP_VERSION,
                    'laravel_version' => app()->version(),
                ], $results)
            );

            if ($isHealthy) {
                $this->info('Health check passed. Application is healthy.');

                return self::SUCCESS;
            }

            return self::FAILURE;
        } catch (Throwable $e) {
            SentinelActor::sendHealthStatus(false, 'Health check crashed: '.$e->getMessage(), [
                'checked_at' => now()->toISOString(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->error('Health check crashed: '.$e->getMessage());

            return self::FAILURE;
        }
    }

    /**
     * Perform basic health checks.
     *
     * @return array<string, array{status: bool, message: string|null}>
     */
    private function performHealthChecks(): array
    {
        $checks = [];

        // Database connection check
        try {
            DB::connection()->getPdo();
            $checks['database'] = ['status' => true, 'message' => null];
        } catch (Throwable $e) {
            $checks['database'] = ['status' => false, 'message' => $e->getMessage()];
        }

        // Storage writability check
        $checks['storage_writable'] = [
            'status' => is_writable(Storage::path('')),
            'message' => is_writable(Storage::path('')) ? null : 'Storage directory is not writable',
        ];

        // Cache check
        try {
            cache()->set('health_check', true, 5);
            $checks['cache'] = ['status' => true, 'message' => null];
        } catch (Throwable $e) {
            $checks['cache'] = ['status' => false, 'message' => $e->getMessage()];
        }

        // Queue check
        try {
            $queue = app('queue');
            $queue->pushRaw('health_check');
            $checks['queue'] = ['status' => true, 'message' => null];
        } catch (Throwable $e) {
            $checks['queue'] = ['status' => false, 'message' => $e->getMessage()];
        }

        // Mail check
        try {
            $mailer = app('mailer');
            $mailer->getSwiftMailer();
            $checks['mail'] = ['status' => true, 'message' => null];
        } catch (Throwable $e) {
            $checks['mail'] = ['status' => false, 'message' => $e->getMessage()];
        }

        return $checks;
    }
}
