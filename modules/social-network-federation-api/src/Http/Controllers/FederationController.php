<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Federation\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\SocialNetwork\Federation\Actions\ReceiveActivity;
use Liberu\SocialNetwork\Federation\Actions\RegisterActor;
use Liberu\SocialNetwork\Federation\Models\FederationMessage;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;

final class FederationController extends Controller
{
    public function inbox(Request $request, ReceiveActivity $receive): JsonResponse
    {
        $payload = $request->validate([
            'id' => ['sometimes', 'string', 'max:500'],
            'type' => ['required', 'string', 'max:80'],
            'actor' => ['sometimes', 'string', 'max:500'],
            'object' => ['sometimes'],
            'to' => ['sometimes', 'array'],
            'cc' => ['sometimes', 'array'],
        ]);
        $message = $receive->handle($payload, $request->header('Signature'));

        return response()->json(['data' => ['id' => $message->getKey(), 'state' => $message->state]], 202);
    }

    public function outbox(Request $request): JsonResponse
    {
        $messages = FederationMessage::query()
            ->where('direction', 'outbound')
            ->latest()
            ->limit(50)
            ->get();

        return response()->json(['data' => $messages->map(fn (FederationMessage $message): array => [
            'id' => $message->getKey(),
            'type' => $message->activity_type,
            'payload' => $message->payload,
            'state' => $message->state,
        ])->values()]);
    }

    public function actor(Request $request, GetProfile $get, RegisterActor $register): JsonResponse
    {
        $data = $request->validate([
            'handle' => ['required', 'string', 'max:190'],
            'actor_url' => ['required', 'url', 'max:500'],
            'inbox_url' => ['required', 'url', 'max:500'],
            'outbox_url' => ['sometimes', 'url', 'max:500'],
            'public_key' => ['sometimes', 'array'],
        ]);
        $actor = $register->handle($get->forUser($request->user()->getAuthIdentifier()), $data);

        return response()->json(['data' => $actor], 201);
    }
}
