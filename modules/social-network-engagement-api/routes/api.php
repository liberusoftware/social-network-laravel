<?php

declare(strict_types=1);
use Illuminate\Support\Facades\Route;
use Liberu\SocialNetwork\Engagement\Api\Http\Controllers\EngagementController;

Route::prefix('api/v1/social-network/engagement')->middleware(['auth:sanctum', 'throttle:60,1'])->name('social-network.engagement.api.')->group(function (): void {
    Route::post('/', [EngagementController::class, 'store'])->name('store');
});
