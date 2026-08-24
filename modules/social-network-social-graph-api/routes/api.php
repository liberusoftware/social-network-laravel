<?php

declare(strict_types=1);
use Illuminate\Support\Facades\Route;
use Liberu\SocialNetwork\SocialGraph\Api\Http\Controllers\SocialGraphController;

Route::prefix('api/v1/social-network/social-graph')->middleware(['auth:sanctum', 'throttle:60,1'])->name('social-network.social-graph.api.')->group(function (): void {
    Route::post('/me/following/{profile}', [SocialGraphController::class, 'follow'])->name('follow');
    Route::post('/me/friend-requests/{profile}', [SocialGraphController::class, 'friend'])->name('friend');
    Route::get('/me/relationships', [SocialGraphController::class, 'index'])->name('index');
    Route::get('/me/suggestions', [SocialGraphController::class, 'suggestions'])->name('suggestions');
});
