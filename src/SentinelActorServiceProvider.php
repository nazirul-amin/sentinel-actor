<?php

namespace NazirulAmin\SentinelActor;

use Illuminate\Support\ServiceProvider;
use NazirulAmin\SentinelActor\Commands\SentinelActorCommand;

class SentinelActorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sentinel-actor.php', 'sentinel-actor');

        $this->app->singleton(SentinelActor::class, function () {
            return new SentinelActor;
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/sentinel-actor.php' => config_path('sentinel-actor.php'),
            ], 'sentinel-actor-config');
        }

        $this->commands([
            SentinelActorCommand::class,
        ]);
    }
}
