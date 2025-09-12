<?php

use Illuminate\Support\Facades\Route;
use NazirulAmin\LaravelMonitoringClient\Http\Controllers\WebhookController;

Route::post('/webhook/monitoring', [WebhookController::class, 'handle']);
