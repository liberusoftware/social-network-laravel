<?php

declare(strict_types=1);
use Illuminate\Support\Facades\Route;
use Liberu\SocialNetwork\Discovery\Api\Http\Controllers\DiscoveryController;

Route::prefix('api/v1/social-network/discovery')->middleware(['auth:sanctum', 'throttle:60,1'])->name('social-network.discovery.api.')->group(function (): void {
    Route::get('/search', [DiscoveryController::class, 'search'])->name('search');
    Route::get('/trends', [DiscoveryController::class, 'trends'])->name('trends');
    Route::post('/index', [DiscoveryController::class, 'index'])->name('index');
});
