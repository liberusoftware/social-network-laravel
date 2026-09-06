<?php

declare(strict_types=1);
use Illuminate\Support\Facades\Route;
use Liberu\SocialNetwork\SocialGraph\Api\Http\Controllers\SocialGraphController;

Route::prefix('api/v1/social-network/social-graph')->middleware(['auth:sanctum', 'throttle:60,1'])->name('social-network.social-graph.api.')->group(function (): void {
    Route::post('/me/following/{profile}', [SocialGraphController::class, 'follow'])->name('follow');
    Route::post('/me/friend-requests/{profile}', [SocialGraphController::class, 'friend'])->name('friend');
    Route::get('/me/relationships', [SocialGraphController::class, 'index'])->name('index');
    Route::get('/me/suggestions', [SocialGraphController::class, 'suggestions'])->name('suggestions');
    Route::put('/me/blocks/{profile}', [SocialGraphController::class, 'block'])->name('block');
    Route::delete('/me/blocks/{profile}', [SocialGraphController::class, 'unblock'])->name('unblock');
    Route::post('/me/relationships/{relationship}/accept', [SocialGraphController::class, 'accept'])->name('accept');
    Route::post('/me/relationships/{relationship}/reject', [SocialGraphController::class, 'reject'])->name('reject');
    Route::post('/me/relationships/{relationship}/cancel', [SocialGraphController::class, 'cancel'])->name('cancel');
    Route::patch('/me/relationships/{relationship}/visibility', [SocialGraphController::class, 'visibility'])->name('visibility');
    Route::get('/me/lists', [SocialGraphController::class, 'lists'])->name('lists');
    Route::post('/me/lists', [SocialGraphController::class, 'createList'])->name('lists.create');
    Route::put('/me/lists/{list}/profiles/{profile}', [SocialGraphController::class, 'addListMember'])->name('lists.profiles.add');
    Route::delete('/me/lists/{list}/profiles/{profile}', [SocialGraphController::class, 'removeListMember'])->name('lists.profiles.remove');
});
