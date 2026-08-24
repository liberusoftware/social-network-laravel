<?php

declare(strict_types=1);
use Illuminate\Support\Facades\Route;
use Liberu\SocialNetwork\Publishing\Api\Http\Controllers\PublishingController;

Route::prefix('api/v1/social-network/publishing')->middleware(['auth:sanctum', 'throttle:60,1'])->name('social-network.publishing.api.')->group(function (): void {
    Route::post('/publications', [PublishingController::class, 'store'])->name('store');
    Route::post('/publications/{publication}/publish', [PublishingController::class, 'publish'])->name('publish');
});
