<?php

declare(strict_types=1);
use Illuminate\Support\Facades\Route;
use Liberu\SocialNetwork\Media\Api\Http\Controllers\MediaController;

Route::prefix('api/v1/social-network/media')->middleware(['auth:sanctum', 'throttle:30,1'])->name('social-network.media.api.')->group(function (): void {
    Route::get('/', [MediaController::class, 'index'])->name('index');
    Route::get('/albums', [MediaController::class, 'albums'])->name('albums.index');
    Route::post('/albums', [MediaController::class, 'storeAlbum'])->name('albums.store');
    Route::get('/albums/{album}', [MediaController::class, 'showAlbum'])->name('albums.show');
    Route::patch('/albums/{album}', [MediaController::class, 'updateAlbum'])->name('albums.update');
    Route::delete('/albums/{album}', [MediaController::class, 'destroyAlbum'])->name('albums.destroy');
    Route::post('/', [MediaController::class, 'store'])->name('store');
    Route::post('/{asset}/ready', [MediaController::class, 'ready'])->name('ready');
    Route::get('/{asset}', [MediaController::class, 'show'])->name('show');
    Route::patch('/{asset}', [MediaController::class, 'update'])->name('update');
    Route::delete('/{asset}', [MediaController::class, 'destroy'])->name('destroy');
});
