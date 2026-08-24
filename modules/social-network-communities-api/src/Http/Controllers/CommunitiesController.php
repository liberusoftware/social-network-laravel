<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Communities\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\SocialNetwork\Communities\Actions\CreateCommunity;
use Liberu\SocialNetwork\Communities\Actions\JoinCommunity;
use Liberu\SocialNetwork\Communities\Models\Community;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;

final class CommunitiesController extends Controller
{
    public function store(Request $request, GetProfile $get, CreateCommunity $create): JsonResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:120'], 'description' => ['nullable', 'string', 'max:10000'], 'visibility' => ['sometimes', 'in:public,private'], 'rules' => ['sometimes', 'array']]);
        $c = $create->handle($get->forUser($request->user()->getAuthIdentifier()), $data);

        return response()->json(['data' => $this->resource($c)], 201);
    }

    public function join(string $community, Request $request, GetProfile $get, JoinCommunity $join): JsonResponse
    {
        $c = Community::query()->findOrFail($community);
        $membership = $join->handle($get->forUser($request->user()->getAuthIdentifier()), $c);

        return response()->json(['data' => ['community_id' => $membership->community_id, 'profile_id' => $membership->profile_id, 'role' => $membership->role, 'status' => $membership->status]], 202);
    }

    private function resource(Community $c): array
    {
        return ['id' => $c->getKey(), 'type' => 'social-network-communities', 'name' => $c->name, 'slug' => $c->slug, 'description' => $c->description, 'visibility' => $c->visibility, 'rules' => $c->rules];
    }
}
