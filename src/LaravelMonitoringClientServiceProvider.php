<?php

namespace NazirulAmin\LaravelMonitoringClient;

use Illuminate\Support\ServiceProvider;
use NazirulAmin\LaravelMonitoringClient\Commands\LaravelMonitoringClientCommand;

class LaravelMonitoringClientServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/monitoring-client.php', 'monitoring-client');

        $this->app->singleton(LaravelMonitoringClient::class, function () {
            return new LaravelMonitoringClient;
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/monitoring-client.php' => config_path('monitoring-client.php'),
            ], 'monitoring-client-config');

            $this->publishes([
                __DIR__.'/../database/migrations/create_monitoring_client_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_monitoring_client_table.php'),
            ], 'monitoring-client-migrations');
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'monitoring-client');

        $this->commands([
            LaravelMonitoringClientCommand::class,
        ]);
    }
}
