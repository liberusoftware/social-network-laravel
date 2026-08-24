<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Liberu\Webhooks\Api\Http\Controllers\WebhooksController;

Route::prefix('api/v1/webhooks')->middleware(['auth:sanctum', 'throttle:60,1'])->group(function (): void {
    Route::get('/', [WebhooksController::class, 'index']);
    Route::post('/', [WebhooksController::class, 'store']);
    Route::post('/{endpoint}/rotate-secret', [WebhooksController::class, 'rotate']);
    Route::get('/{endpoint}/deliveries', [WebhooksController::class, 'deliveries']);
    Route::post('/{endpoint}/deliveries/{delivery}/replay', [WebhooksController::class, 'replay']);
});
