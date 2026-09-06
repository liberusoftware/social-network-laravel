<?php

declare(strict_types=1);
use Illuminate\Support\Facades\Route;
use Liberu\SocialNetwork\Publishing\Api\Http\Controllers\PublishingController;

Route::prefix('api/v1/social-network/publishing')->middleware(['auth:sanctum', 'throttle:60,1'])->name('social-network.publishing.api.')->group(function (): void {
    Route::get('/publications', [PublishingController::class, 'index'])->name('index');
    Route::post('/publications', [PublishingController::class, 'store'])->name('store');
    Route::get('/publications/{publication}', [PublishingController::class, 'show'])->name('show');
    Route::patch('/publications/{publication}', [PublishingController::class, 'update'])->name('update');
    Route::patch('/publications/{publication}/enrichments', [PublishingController::class, 'enrichments'])->name('enrichments');
    Route::delete('/publications/{publication}', [PublishingController::class, 'destroy'])->name('destroy');
    Route::post('/publications/{publication}/publish', [PublishingController::class, 'publish'])->name('publish');
});
