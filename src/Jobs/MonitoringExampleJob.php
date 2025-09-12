<?php

namespace NazirulAmin\LaravelMonitoringClient\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use NazirulAmin\LaravelMonitoringClient\Traits\MonitorsExceptions;

class MonitoringExampleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, MonitorsExceptions, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Your job logic here
        // If an exception is thrown, the failed() method in MonitorsExceptions will be called automatically
        throw new \Exception('Example exception for monitoring');
    }

    /**
     * Get monitoring context data.
     */
    protected function getMonitoringContextData(): array
    {
        return [
            'data' => $this->data,
        ];
    }
}
