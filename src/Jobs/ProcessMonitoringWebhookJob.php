<?php

namespace NazirulAmin\LaravelMonitoringClient\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use Spatie\WebhookClient\Models\WebhookCall;

class ProcessMonitoringWebhookJob extends ProcessWebhookJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(WebhookCall $webhookCall)
    {
        parent::__construct($webhookCall);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $payload = $this->webhookCall->payload;

        // Process the webhook data based on type
        switch ($payload['type'] ?? null) {
            case 'exception':
                $this->processException($payload);
                break;

            default:
                \Log::info('Received unknown webhook type', ['payload' => $payload]);
                break;
        }
    }

    /**
     * Process exception data
     */
    protected function processException(array $data): void
    {
        // Store exception data in database
        \NazirulAmin\LaravelMonitoringClient\Models\MonitoringEvent::create([
            'application_id' => $data['application_id'],
            'event_type' => 'exception',
            'level' => 'error',
            'message' => $data['message'] ?? null,
            'context' => [
                'file' => $data['file'] ?? null,
                'line' => $data['line'] ?? null,
                'code' => $data['code'] ?? null,
                'trace' => $data['trace'] ?? null,
                'context' => $data['context'] ?? null,
            ],
            'timestamp' => date('Y-m-d H:i:s', $data['timestamp']),
        ]);
    }
}
