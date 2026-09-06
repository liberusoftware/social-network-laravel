<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\SocialGraph\Actions\AcceptFriendship;
use Liberu\SocialNetwork\SocialGraph\Actions\AddProfileToList;
use Liberu\SocialNetwork\SocialGraph\Actions\BlockProfile;
use Liberu\SocialNetwork\SocialGraph\Actions\CancelFriendship;
use Liberu\SocialNetwork\SocialGraph\Actions\CreateList;
use Liberu\SocialNetwork\SocialGraph\Actions\CreateRelationship;
use Liberu\SocialNetwork\SocialGraph\Actions\ListOwnedLists;
use Liberu\SocialNetwork\SocialGraph\Actions\ListRelationships;
use Liberu\SocialNetwork\SocialGraph\Actions\RejectFriendship;
use Liberu\SocialNetwork\SocialGraph\Actions\RemoveProfileFromList;
use Liberu\SocialNetwork\SocialGraph\Actions\SuggestProfiles;
use Liberu\SocialNetwork\SocialGraph\Actions\UnblockProfile;
use Liberu\SocialNetwork\SocialGraph\Actions\UpdateRelationshipVisibility;
use Liberu\SocialNetwork\SocialGraph\Models\GraphList;
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

    public function index(Request $request, GetProfile $get, ListRelationships $list): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['sometimes', 'string', 'in:follow,friend'],
            'status' => ['sometimes', 'string', 'in:pending,accepted,rejected,cancelled'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);
        $profile = $get->forUser($request->user()->getAuthIdentifier());

        return response()->json(['data' => $list->handle($profile, $validated['type'] ?? null, $validated['status'] ?? null, $validated['limit'] ?? 100)->map(fn (Relationship $r) => $this->resource($r))->values()]);
    }

    public function suggestions(Request $request, GetProfile $get, SuggestProfiles $suggest): JsonResponse
    {
        $profile = $get->forUser($request->user()->getAuthIdentifier());

        return response()->json(['data' => $suggest->for($profile)->map(fn ($p): array => ['id' => $p->getKey(), 'handle' => $p->handle])->values()]);
    }

    public function block(string $profile, Request $request, GetProfile $get, BlockProfile $block): JsonResponse
    {
        $source = $get->forUser($request->user()->getAuthIdentifier());
        $created = $block->handle($source, $get->byId($profile));

        return response()->json(['data' => [
            'id' => $created->getKey(), 'type' => 'social-network-social-graph-blocks',
            'source_profile_id' => $created->source_profile_id, 'target_profile_id' => $created->target_profile_id,
        ]], 201);
    }

    public function unblock(string $profile, Request $request, GetProfile $get, UnblockProfile $unblock): JsonResponse
    {
        $unblock->handle($get->forUser($request->user()->getAuthIdentifier()), $get->byId($profile));

        return response()->json([], 204);
    }

    public function accept(Relationship $relationship, Request $request, GetProfile $get, AcceptFriendship $accept): JsonResponse
    {
        return response()->json(['data' => $this->resource($accept->handle($get->forUser($request->user()->getAuthIdentifier()), $relationship))]);
    }

    public function reject(Relationship $relationship, Request $request, GetProfile $get, RejectFriendship $reject): JsonResponse
    {
        return response()->json(['data' => $this->resource($reject->handle($get->forUser($request->user()->getAuthIdentifier()), $relationship))]);
    }

    public function cancel(Relationship $relationship, Request $request, GetProfile $get, CancelFriendship $cancel): JsonResponse
    {
        return response()->json(['data' => $this->resource($cancel->handle($get->forUser($request->user()->getAuthIdentifier()), $relationship))]);
    }

    public function visibility(Relationship $relationship, Request $request, GetProfile $get, UpdateRelationshipVisibility $update): JsonResponse
    {
        $validated = $request->validate(['visibility' => ['required', 'string', 'in:public,followers,private']]);
        $actor = $get->forUser($request->user()->getAuthIdentifier());

        return response()->json(['data' => $this->resource($update->handle($actor, $relationship, $validated['visibility']))]);
    }

    public function lists(Request $request, GetProfile $get, ListOwnedLists $lists): JsonResponse
    {
        $owner = $get->forUser($request->user()->getAuthIdentifier());

        return response()->json(['data' => $lists->handle($owner)->map(fn (GraphList $list) => $this->listResource($list))->values()]);
    }

    public function createList(Request $request, GetProfile $get, CreateList $create): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:1', 'max:80'],
            'visibility' => ['sometimes', 'string', 'in:public,followers,private'],
        ]);
        $owner = $get->forUser($request->user()->getAuthIdentifier());

        return response()->json(['data' => $this->listResource($create->handle($owner, $data))], 201);
    }

    public function addListMember(GraphList $list, string $profile, Request $request, GetProfile $get, AddProfileToList $add): JsonResponse
    {
        $owner = $get->forUser($request->user()->getAuthIdentifier());

        return response()->json(['data' => $this->listResource($add->handle($owner, $list, $get->byId($profile)))]);
    }

    public function removeListMember(GraphList $list, string $profile, Request $request, GetProfile $get, RemoveProfileFromList $remove): JsonResponse
    {
        $owner = $get->forUser($request->user()->getAuthIdentifier());

        return response()->json(['data' => $this->listResource($remove->handle($owner, $list, $get->byId($profile)))]);
    }

    /** @return array<string,mixed> */
    private function resource(Relationship $r): array
    {
        return ['id' => $r->getKey(), 'type' => 'social-network-social-graph-relationships', 'source_profile_id' => $r->source_profile_id, 'target_profile_id' => $r->target_profile_id, 'relationship_type' => $r->relationship_type, 'status' => $r->status, 'visibility' => $r->visibility, 'created_at' => $r->created_at?->toISOString()];
    }

    /** @return array<string, mixed> */
    private function listResource(GraphList $list): array
    {
        return [
            'id' => $list->getKey(),
            'type' => 'social-network-social-graph-lists',
            'name' => $list->name,
            'visibility' => $list->visibility,
            'profiles' => $list->relationLoaded('profiles') ? $list->profiles->pluck('id')->values() : [],
            'created_at' => $list->created_at?->toISOString(),
        ];
    }
}
