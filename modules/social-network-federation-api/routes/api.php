<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Liberu\SocialNetwork\Federation\Api\Http\Controllers\FederationController;

Route::prefix('api/v1/social-network/federation')->middleware('api')->group(function (): void {
    Route::post('/inbox', [FederationController::class, 'inbox'])->name('social-network.federation.inbox');
    Route::get('/outbox', [FederationController::class, 'outbox'])->name('social-network.federation.outbox');
    Route::middleware(['auth:sanctum', 'throttle:60,1'])->post('/actors', [FederationController::class, 'actor'])->name('social-network.federation.actors');
});
