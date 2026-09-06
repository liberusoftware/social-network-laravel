<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Liberu\SocialNetwork\Notifications\Actions\DismissNotification;
use Liberu\SocialNetwork\Notifications\Actions\MarkAllRead;
use Liberu\SocialNetwork\Notifications\Actions\MarkRead;
use Liberu\SocialNetwork\Notifications\Actions\UnreadCount;
use Liberu\SocialNetwork\Notifications\Actions\UpdatePreferences;
use Liberu\SocialNetwork\Notifications\Models\SocialNotification;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;

final class NotificationsController extends Controller
{
    public function index(Request $request, GetProfile $get): JsonResponse
    {
        $request->validate([
            'state' => ['sometimes', Rule::in((array) config('social-network-notifications.states'))],
            'channel' => ['sometimes', Rule::in((array) config('social-network-notifications.channels'))],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);
        $profile = $get->forUser($request->user()->getAuthIdentifier());
        $query = SocialNotification::query()->where('profile_id', $profile->getKey());
        if ($request->filled('state')) {
            $query->where('state', $request->string('state')->toString());
        }
        if ($request->filled('channel')) {
            $query->where('channel', $request->string('channel')->toString());
        }
        $items = $query->latest()->limit(min($request->integer('limit', 50), 100))->get();

        return response()->json(['data' => $items->map(fn ($n) => ['id' => $n->getKey(), 'type' => 'social-network-notifications', 'kind' => $n->kind, 'state' => $n->state, 'channel' => $n->channel, 'payload' => $n->payload, 'read_at' => $n->read_at?->toISOString()])->values()]);
    }

    public function preferences(Request $request, GetProfile $get, UpdatePreferences $update): JsonResponse
    {
        $data = $request->validate(['channels' => ['sometimes', 'array'], 'quiet_hours' => ['sometimes', 'array'], 'digest' => ['sometimes', 'array']]);
        $p = $update->handle($get->forUser($request->user()->getAuthIdentifier()), $data);

        return response()->json(['data' => $p->only(['profile_id', 'channels', 'quiet_hours', 'digest'])]);
    }

    public function unreadCount(Request $request, GetProfile $get, UnreadCount $count): JsonResponse
    {
        return response()->json(['count' => $count->handle($get->forUser($request->user()->getAuthIdentifier()))]);
    }

    public function readAll(Request $request, GetProfile $get, MarkAllRead $read): JsonResponse
    {
        return response()->json(['marked' => $read->handle($get->forUser($request->user()->getAuthIdentifier()))]);
    }

    public function read(string $notification, Request $request, GetProfile $get, MarkRead $read): JsonResponse
    {
        $item = $read->handle($get->forUser($request->user()->getAuthIdentifier()), $notification);

        return response()->json(['data' => ['id' => $item->getKey(), 'state' => $item->state, 'read_at' => $item->read_at?->toISOString()]]);
    }

    public function dismiss(string $notification, Request $request, GetProfile $get, DismissNotification $dismiss): JsonResponse
    {
        $item = $dismiss->handle($get->forUser($request->user()->getAuthIdentifier()), $notification);

        return response()->json(['data' => ['id' => $item->getKey(), 'state' => $item->state]]);
    }
}
