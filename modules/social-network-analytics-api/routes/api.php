<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Liberu\SocialNetwork\Analytics\Api\Http\Controllers\AnalyticsController;

Route::prefix('api/v1/social-network/analytics')
    ->middleware(['auth:sanctum', 'throttle:60,1'])
    ->group(function (): void {
        Route::get('/metrics/{metric}', [AnalyticsController::class, 'show'])->name('social-network.analytics.metrics');
    });
