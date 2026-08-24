<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Liberu\SocialNetwork\Events\Api\Http\Controllers\EventsController;

Route::prefix('api/v1/social-network/events')
    ->middleware(['auth:sanctum', 'throttle:60,1'])
    ->name('social-network.events.api.')
    ->group(function (): void {
        Route::get('/', [EventsController::class, 'index'])->name('index');
        Route::post('/', [EventsController::class, 'store'])->name('store');
        Route::patch('/{event}', [EventsController::class, 'update'])->name('update');
        Route::post('/{event}/publish', [EventsController::class, 'publish'])->name('publish');
        Route::post('/{event}/invitations', [EventsController::class, 'invite'])->name('invite');
        Route::post('/{event}/attendance', [EventsController::class, 'attendance'])->name('attendance');
    });
