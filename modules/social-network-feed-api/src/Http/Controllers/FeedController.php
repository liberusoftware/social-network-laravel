<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Feed\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\SocialNetwork\Feed\Actions\GetFeed;
use Liberu\SocialNetwork\Feed\Actions\UpdateFeedControls;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;

final class FeedController extends Controller
{
    public function index(Request $request, GetProfile $get, GetFeed $feed): JsonResponse
    {
        $data = $request->validate(['limit' => ['sometimes', 'integer', 'min:1', 'max:100'], 'after' => ['sometimes', 'nullable', 'uuid']]);
        $entries = $feed->handle($get->forUser($request->user()->getAuthIdentifier()), $data['limit'] ?? 20, $data['after'] ?? null);

        return response()->json([
            'data' => $entries->map(fn ($e) => [
                'id' => $e->getKey(),
                'type' => 'social-network-feed-entry',
                'attributes' => [
                    'item_type' => $e->item_type,
                    'item_id' => $e->item_id,
                    'rank' => $e->rank,
                    'visible_at' => $e->visible_at?->toISOString(),
                    'explanation' => $e->item_type === 'publication' ? 'from a publication in your feed' : 'recommended for your feed',
                ],
            ])->values(),
            'meta' => ['limit' => $data['limit'] ?? 20, 'next_after' => $entries->last()?->getKey()],
        ]);
    }

    public function controls(Request $request, GetProfile $get, UpdateFeedControls $update): JsonResponse
    {
        $data = $request->validate(['mode' => ['sometimes', 'in:ranked,chronological'], 'filters' => ['sometimes', 'array', 'max:20'], 'hidden_items' => ['sometimes', 'array', 'max:500']]);

        return response()->json(['data' => $update->handle($get->forUser($request->user()->getAuthIdentifier()), $data)]);
    }
}
