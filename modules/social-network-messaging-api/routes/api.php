<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Liberu\SocialNetwork\Messaging\Api\Http\Controllers\MessagingController;

Route::prefix('api/v1/social-network/messaging')
    ->middleware(['auth:sanctum', 'throttle:60,1'])
    ->name('social-network.messaging.api.')
    ->group(function (): void {
        Route::get('/conversations', [MessagingController::class, 'index'])->name('index');
        Route::post('/conversations', [MessagingController::class, 'conversation'])->name('conversation');
        Route::post('/conversations/{conversation}/messages', [MessagingController::class, 'message'])->name('message');
        Route::post('/conversations/{conversation}/messages/{message}/reactions', [MessagingController::class, 'reaction'])->name('reaction');
        Route::delete('/conversations/{conversation}/messages/{message}/reactions/{emoji}', [MessagingController::class, 'removeReaction'])->name('reaction.remove');
        Route::post('/conversations/{conversation}/typing', [MessagingController::class, 'typing'])->name('typing');
        Route::post('/conversations/{conversation}/read', [MessagingController::class, 'read'])->name('read');
    });
