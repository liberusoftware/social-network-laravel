<?php

declare(strict_types=1);
use Illuminate\Support\Facades\Route;
use Liberu\SocialNetwork\Communities\Api\Http\Controllers\CommunitiesController;

Route::prefix('api/v1/social-network/communities')->middleware(['auth:sanctum', 'throttle:60,1'])->name('social-network.communities.api.')->group(function (): void {
    Route::get('/', [CommunitiesController::class, 'index'])->name('index');
    Route::post('/', [CommunitiesController::class, 'store'])->name('store');
    Route::post('/{community}/join', [CommunitiesController::class, 'join'])->name('join');
    Route::delete('/{community}/leave', [CommunitiesController::class, 'leave'])->name('leave');
});
