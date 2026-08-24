<?php

declare(strict_types=1);
use Illuminate\Support\Facades\Route;
use Liberu\SocialNetwork\Notifications\Api\Http\Controllers\NotificationsController;

Route::prefix('api/v1/social-network/notifications')->middleware(['auth:sanctum', 'throttle:60,1'])->name('social-network.notifications.api.')->group(function (): void {
    Route::get('/', [NotificationsController::class, 'index'])->name('index');
    Route::patch('/preferences', [NotificationsController::class, 'preferences'])->name('preferences');
    Route::post('/{notification}/read', [NotificationsController::class, 'read'])->name('read');
    Route::post('/{notification}/dismiss', [NotificationsController::class, 'dismiss'])->name('dismiss');
});
