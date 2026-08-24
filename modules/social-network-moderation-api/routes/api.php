<?php

declare(strict_types=1);
use Illuminate\Support\Facades\Route;
use Liberu\SocialNetwork\Moderation\Api\Http\Controllers\ModerationController;

Route::prefix('api/v1/social-network/moderation')->middleware(['auth:sanctum', 'throttle:30,1'])->name('social-network.moderation.api.')->group(function (): void {
    Route::post('/reports', [ModerationController::class, 'report'])->name('report');
    Route::post('/reports/{report}/decide', [ModerationController::class, 'decide'])->name('decide');
});
