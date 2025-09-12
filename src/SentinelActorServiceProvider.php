<?php

namespace NazirulAmin\SentinelActor;

use Illuminate\Support\ServiceProvider;
use NazirulAmin\SentinelActor\Commands\SentinelActorCommand;

class SentinelActorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/monitoring-client.php', 'monitoring-client');

        $this->app->singleton(SentinelActor::class, function () {
            return new SentinelActor;
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/monitoring-client.php' => config_path('monitoring-client.php'),
            ], 'sentinel-actor-config');

            $this->publishes([
                __DIR__.'/../database/migrations/create_monitoring_client_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_monitoring_client_table.php'),
            ], 'sentinel-actor-migrations');
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'monitoring-client');

        $this->commands([
            SentinelActorCommand::class,
        ]);
    }
}
