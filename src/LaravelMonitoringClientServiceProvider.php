<?php

namespace NazirulAmin\LaravelMonitoringClient;

use NazirulAmin\LaravelMonitoringClient\Commands\LaravelMonitoringClientCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelMonitoringClientServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-monitoring-client')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_monitoring_client_table')
            ->hasCommand(LaravelMonitoringClientCommand::class);
    }
}
