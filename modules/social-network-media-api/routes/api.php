<?php

declare(strict_types=1);
use Illuminate\Support\Facades\Route;
use Liberu\SocialNetwork\Media\Api\Http\Controllers\MediaController;

Route::prefix('api/v1/social-network/media')->middleware(['auth:sanctum', 'throttle:30,1'])->name('social-network.media.api.')->group(function (): void {
    Route::get('/', [MediaController::class, 'index'])->name('index');
    Route::post('/', [MediaController::class, 'store'])->name('store');
    Route::post('/{asset}/ready', [MediaController::class, 'ready'])->name('ready');
    Route::delete('/{asset}', [MediaController::class, 'destroy'])->name('destroy');
});
