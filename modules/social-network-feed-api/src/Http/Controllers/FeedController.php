<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Feed\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\SocialNetwork\Feed\Actions\GetFeed;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;

final class FeedController extends Controller
{
    public function index(Request $request, GetProfile $get, GetFeed $feed): JsonResponse
    {
        $data = $request->validate(['limit' => ['sometimes', 'integer', 'min:1', 'max:100'], 'after' => ['sometimes', 'nullable', 'uuid']]);
        $entries = $feed->handle($get->forUser($request->user()->getAuthIdentifier()), $data['limit'] ?? 20, $data['after'] ?? null);

        return response()->json(['data' => $entries->map(fn ($e) => ['id' => $e->getKey(), 'type' => 'social-network-feed-entries', 'item_type' => $e->item_type, 'item_id' => $e->item_id, 'rank' => $e->rank, 'visible_at' => $e->visible_at?->toISOString()])->values()]);
    }
}
