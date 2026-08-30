<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Liberu\SocialNetwork\Profiles\Api\Http\Controllers\ProfilesController;

Route::prefix('api/v1/social-network/profiles')->middleware(['auth:sanctum', 'throttle:60,1'])->name('social-network.profiles.api.')->group(function (): void {
    Route::get('/me', [ProfilesController::class, 'me'])->name('me');
    Route::patch('/me', [ProfilesController::class, 'update'])->name('update');
    Route::post('/me/blocks/{profile}', [ProfilesController::class, 'block'])->name('block');
    Route::delete('/me/blocks/{profile}', [ProfilesController::class, 'unblock'])->name('unblock');
    Route::patch('/me/lifecycle', [ProfilesController::class, 'lifecycle'])->name('lifecycle');
    Route::patch('/{profile}/verification', [ProfilesController::class, 'verification'])->name('verification');
    Route::get('/{profile}', [ProfilesController::class, 'show'])->name('show');
});
