<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\Publishing\Actions\CreatePublication;
use Liberu\SocialNetwork\Publishing\Actions\PublishPublication;
use Liberu\SocialNetwork\Publishing\Models\Publication;

final class PublishingController extends Controller
{
    public function store(Request $request, GetProfile $get, CreatePublication $create): JsonResponse
    {
        $data = $request->validate(['kind' => ['sometimes', 'in:post,article'], 'audience' => ['sometimes', 'in:public,followers,private'], 'title' => ['nullable', 'string', 'max:240'], 'body' => ['nullable', 'string', 'max:100000'], 'metadata' => ['sometimes', 'array']]);
        $publication = $create->handle($get->forUser($request->user()->getAuthIdentifier()), $data);

        return response()->json(['data' => $this->resource($publication)], 201);
    }

    public function publish(string $publication, Request $request, GetProfile $get, PublishPublication $publish): JsonResponse
    {
        $item = Publication::query()->findOrFail($publication);
        $item = $publish->handle($get->forUser($request->user()->getAuthIdentifier()), $item);

        return response()->json(['data' => $this->resource($item)]);
    }

    /** @return array<string,mixed> */
    private function resource(Publication $p): array
    {
        return ['id' => $p->getKey(), 'type' => 'social-network-publications', 'kind' => $p->kind, 'state' => $p->state, 'audience' => $p->audience, 'title' => $p->title, 'body' => $p->body, 'metadata' => $p->metadata, 'published_at' => $p->published_at?->toISOString()];
    }
}
