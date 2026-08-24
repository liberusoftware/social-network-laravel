<?php

declare(strict_types=1);
use Illuminate\Support\Facades\Route;
use Liberu\SocialNetwork\Feed\Api\Http\Controllers\FeedController;

Route::prefix('api/v1/social-network/feed')->middleware(['auth:sanctum', 'throttle:60,1'])->name('social-network.feed.api.')->group(function (): void {
    Route::get('/', [FeedController::class, 'index'])->name('index');
});
