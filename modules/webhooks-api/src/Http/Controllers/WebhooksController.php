<?php

declare(strict_types=1);

namespace Liberu\Webhooks\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\Foundation\Webhooks\Actions\DeliverWebhook;
use Liberu\Foundation\Webhooks\Actions\RegisterEndpoint;
use Liberu\Foundation\Webhooks\Actions\RotateEndpointSecret;
use Liberu\Foundation\Webhooks\Models\WebhookDelivery;
use Liberu\Foundation\Webhooks\Models\WebhookEndpoint;

final class WebhooksController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(['data' => WebhookEndpoint::query()->where('owner_ref', (string) $request->user()->getAuthIdentifier())->latest()->get()->map(fn (WebhookEndpoint $endpoint): array => $this->endpoint($endpoint))->values()]);
    }

    public function store(Request $request, RegisterEndpoint $register): JsonResponse
    {
        $data = $request->validate([
            'url' => ['required', 'url', 'max:2048'],
            'events' => ['required', 'array', 'min:1', 'max:100'],
            'events.*' => ['string', 'max:120'],
        ]);
        $endpoint = $register->handle((string) $request->user()->getAuthIdentifier(), $data['url'], $data['events']);

        return response()->json(['data' => $this->endpoint($endpoint)], 201);
    }

    public function rotate(WebhookEndpoint $endpoint, Request $request, RotateEndpointSecret $rotate): JsonResponse
    {
        $this->owned($endpoint, $request);

        return response()->json(['data' => ['endpoint_id' => $endpoint->getKey(), 'secret' => $rotate->handle($endpoint)]]);
    }

    public function deliveries(WebhookEndpoint $endpoint, Request $request): JsonResponse
    {
        $this->owned($endpoint, $request);
        $deliveries = WebhookDelivery::query()->where('endpoint_id', $endpoint->getKey())->latest()->limit(100)->get();

        return response()->json(['data' => $deliveries]);
    }

    public function replay(WebhookEndpoint $endpoint, WebhookDelivery $delivery, Request $request, DeliverWebhook $deliver): JsonResponse
    {
        $this->owned($endpoint, $request);
        abort_unless((int) $delivery->endpoint_id === (int) $endpoint->getKey(), 404);
        $delivery->forceFill(['status' => 'pending', 'next_attempt_at' => null])->save();

        return response()->json(['data' => $deliver->handle($delivery)]);
    }

    private function owned(WebhookEndpoint $endpoint, Request $request): void
    {
        abort_unless((string) $endpoint->owner_ref === (string) $request->user()->getAuthIdentifier(), 404);
    }

    private function endpoint(WebhookEndpoint $endpoint): array
    {
        return ['id' => $endpoint->getKey(), 'url' => $endpoint->url, 'events' => $endpoint->events, 'active' => $endpoint->active, 'rotated_at' => $endpoint->rotated_at?->toISOString()];
    }
}
