<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\SocialNetwork\Notifications\Actions\MarkRead;
use Liberu\SocialNetwork\Notifications\Actions\UpdatePreferences;
use Liberu\SocialNetwork\Notifications\Models\SocialNotification;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;

final class NotificationsController extends Controller
{
    public function index(Request $request, GetProfile $get): JsonResponse
    {
        $profile = $get->forUser($request->user()->getAuthIdentifier());
        $items = SocialNotification::query()->where('profile_id', $profile->getKey())->latest()->limit(50)->get();

        return response()->json(['data' => $items->map(fn ($n) => ['id' => $n->getKey(), 'type' => 'social-network-notifications', 'kind' => $n->kind, 'state' => $n->state, 'channel' => $n->channel, 'payload' => $n->payload, 'read_at' => $n->read_at?->toISOString()])->values()]);
    }

    public function preferences(Request $request, GetProfile $get, UpdatePreferences $update): JsonResponse
    {
        $data = $request->validate(['channels' => ['sometimes', 'array'], 'quiet_hours' => ['sometimes', 'array'], 'digest' => ['sometimes', 'array']]);
        $p = $update->handle($get->forUser($request->user()->getAuthIdentifier()), $data);

        return response()->json(['data' => $p->only(['profile_id', 'channels', 'quiet_hours', 'digest'])]);
    }

    public function read(string $notification, Request $request, GetProfile $get, MarkRead $read): JsonResponse
    {
        $item = $read->handle($get->forUser($request->user()->getAuthIdentifier()), $notification);

        return response()->json(['data' => ['id' => $item->getKey(), 'state' => $item->state, 'read_at' => $item->read_at?->toISOString()]]);
    }
}
