<?php

use Illuminate\Support\Facades\Http;
use Liberu\Foundation\Webhooks\Actions\DeliverWebhook;
use Liberu\Foundation\Webhooks\Actions\QueueWebhook;
use Liberu\Foundation\Webhooks\Actions\RegisterEndpoint;
use Liberu\Foundation\Webhooks\Models\WebhookDelivery;

it('never sends webhook payloads to an internal destination', function (): void {
    Http::fake();
    app(RegisterEndpoint::class)->handle('owner-1', 'http://169.254.169.254/latest/meta-data/', ['post.created']);
    app(QueueWebhook::class)->handle('00000000-0000-0000-0000-000000000001', 'post.created', ['private' => 'payload']);

    $delivery = app(DeliverWebhook::class)->handle(WebhookDelivery::query()->firstOrFail());

    expect($delivery->status)->toBe('failed');
    Http::assertNothingSent();
});
