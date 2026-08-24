<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Liberu\SocialNetwork\SocialCore\Api\Http\Controllers\SocialCoreSettingsController;

Route::prefix('api/v1/social-network/social-core')
    ->middleware(['auth:sanctum', 'throttle:60,1'])
    ->name('social-network.social-core.api.')
    ->group(function (): void {
        Route::get('/', [SocialCoreSettingsController::class, 'show'])->name('show');
        Route::patch('/', [SocialCoreSettingsController::class, 'update'])->name('update');
    });
