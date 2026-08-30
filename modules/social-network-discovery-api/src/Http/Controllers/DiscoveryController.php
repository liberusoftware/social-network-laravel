<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Discovery\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\SocialNetwork\Discovery\Actions\IndexResource;
use Liberu\SocialNetwork\Discovery\Actions\ListTrends;
use Liberu\SocialNetwork\Discovery\Actions\SearchDiscovery;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;

final class DiscoveryController extends Controller
{
    public function search(Request $request, GetProfile $get, SearchDiscovery $search): JsonResponse
    {
        $data = $request->validate(['q' => ['required', 'string', 'max:10000'], 'limit' => ['sometimes', 'integer', 'min:1', 'max:100']]);

        return response()->json(['data' => $search->handle($get->forUser($request->user()->getAuthIdentifier()), $data['q'], $data['limit'] ?? 25)->map(fn ($item) => $this->resource($item))->values()]);
    }

    public function trends(Request $request, GetProfile $get, ListTrends $trends): JsonResponse
    {
        $data = $request->validate(['limit' => ['sometimes', 'integer', 'min:1', 'max:100']]);

        return response()->json(['data' => $trends->handle($get->forUser($request->user()->getAuthIdentifier()), $data['limit'] ?? 20)]);
    }

    public function index(Request $request, GetProfile $get, IndexResource $index): JsonResponse
    {
        $data = $request->validate(['resource_type' => ['required', 'string', 'max:120'], 'resource_id' => ['required', 'uuid'], 'body' => ['required', 'string', 'max:10000'], 'visibility' => ['sometimes', 'in:public,followers,private'], 'terms' => ['sometimes', 'array'], 'engagement_score' => ['sometimes', 'integer', 'min:0', 'max:1000000'], 'published_at' => ['sometimes', 'date']]);

        return response()->json(['data' => $this->resource($index->handle($get->forUser($request->user()->getAuthIdentifier()), $data))], 201);
    }

    private function resource(object $item): array
    {
        return ['id' => $item->getKey(), 'type' => 'social-network-discovery-index', 'resource_type' => $item->resource_type, 'resource_id' => $item->resource_id, 'body' => $item->body, 'visibility' => $item->visibility, 'terms' => $item->terms, 'engagement_score' => $item->engagement_score];
    }
}
