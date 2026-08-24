<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\SocialGraph\Actions\CreateRelationship;
use Liberu\SocialNetwork\SocialGraph\Actions\SuggestProfiles;
use Liberu\SocialNetwork\SocialGraph\Models\Relationship;

final class SocialGraphController extends Controller
{
    public function follow(string $profile, Request $request, GetProfile $get, CreateRelationship $create): JsonResponse
    {
        $r = $create->follow($get->forUser($request->user()->getAuthIdentifier()), $get->byId($profile));

        return response()->json(['data' => $this->resource($r)], 201);
    }

    public function friend(string $profile, Request $request, GetProfile $get, CreateRelationship $create): JsonResponse
    {
        $r = $create->friend($get->forUser($request->user()->getAuthIdentifier()), $get->byId($profile));

        return response()->json(['data' => $this->resource($r)], 202);
    }

    public function index(Request $request): JsonResponse
    {
        $id = $request->user()->getAuthIdentifier();
        $profile = app(GetProfile::class)->forUser($id);

        return response()->json(['data' => Relationship::query()->where('source_profile_id', $profile->getKey())->latest()->get()->map(fn (Relationship $r) => $this->resource($r))->values()]);
    }

    public function suggestions(Request $request, SuggestProfiles $suggest): JsonResponse
    {
        $profile = app(GetProfile::class)->forUser($request->user()->getAuthIdentifier());

        return response()->json(['data' => $suggest->for($profile)->map(fn ($p): array => ['id' => $p->getKey(), 'handle' => $p->handle])->values()]);
    }

    /** @return array<string,mixed> */
    private function resource(Relationship $r): array
    {
        return ['id' => $r->getKey(), 'type' => 'social-network-social-graph-relationships', 'source_profile_id' => $r->source_profile_id, 'target_profile_id' => $r->target_profile_id, 'relationship_type' => $r->relationship_type, 'status' => $r->status, 'visibility' => $r->visibility, 'created_at' => $r->created_at?->toISOString()];
    }
}
