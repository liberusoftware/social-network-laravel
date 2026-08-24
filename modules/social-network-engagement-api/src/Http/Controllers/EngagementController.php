<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Engagement\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\SocialNetwork\Engagement\Actions\CreateEngagement;
use Liberu\SocialNetwork\Engagement\Actions\DeleteEngagement;
use Liberu\SocialNetwork\Engagement\Models\Engagement;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;

final class EngagementController extends Controller
{
    public function store(Request $request, GetProfile $get, CreateEngagement $create): JsonResponse
    {
        $data = $request->validate(['kind' => ['required', 'in:reaction,comment,reply,share,bookmark'], 'reaction_type' => ['nullable', 'string', 'max:24'], 'target_type' => ['required', 'string', 'max:160'], 'target_id' => ['required', 'uuid'], 'body' => ['nullable', 'string', 'max:10000']]);
        $item = $create->handle($get->forUser($request->user()->getAuthIdentifier()), $data);

        return response()->json(['data' => ['id' => $item->getKey(), 'type' => 'social-network-engagements', 'kind' => $item->kind, 'target_type' => $item->target_type, 'target_id' => $item->target_id, 'reaction_type' => $item->reaction_type, 'body' => $item->body]], 201);
    }

    public function destroy(string $engagement, Request $request, GetProfile $get, DeleteEngagement $delete): JsonResponse
    {
        $delete->handle($get->forUser($request->user()->getAuthIdentifier()), Engagement::query()->findOrFail($engagement));
        return response()->json(status: 204);
    }
}
