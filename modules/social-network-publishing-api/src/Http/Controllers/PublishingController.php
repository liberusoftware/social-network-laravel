<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\Publishing\Actions\CreatePublication;
use Liberu\SocialNetwork\Publishing\Actions\DeletePublication;
use Liberu\SocialNetwork\Publishing\Actions\ListPublications;
use Liberu\SocialNetwork\Publishing\Actions\PublishPublication;
use Liberu\SocialNetwork\Publishing\Actions\UpdatePublication;
use Liberu\SocialNetwork\Publishing\Actions\UpdatePublicationEnrichments;
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

    public function index(Request $request, GetProfile $get, ListPublications $list): JsonResponse
    {
        $data = $request->validate(['limit' => ['sometimes', 'integer', 'min:1', 'max:100']]);

        return response()->json(['data' => $list->handle($get->forUser($request->user()->getAuthIdentifier()), $data['limit'] ?? 25)->map(fn (Publication $publication): array => $this->resource($publication))->values()]);
    }

    public function show(string $publication, Request $request, GetProfile $get, ListPublications $list): JsonResponse
    {
        $item = $list->handle($get->forUser($request->user()->getAuthIdentifier()), 100)->firstWhere('id', $publication);
        abort_unless($item !== null, 404);

        return response()->json(['data' => $this->resource($item)]);
    }

    public function update(string $publication, Request $request, GetProfile $get, UpdatePublication $update): JsonResponse
    {
        $data = $request->validate(['kind' => ['sometimes', 'in:post,article'], 'audience' => ['sometimes', 'in:public,followers,private'], 'title' => ['sometimes', 'nullable', 'string', 'max:240'], 'body' => ['sometimes', 'nullable', 'string', 'max:100000'], 'metadata' => ['sometimes', 'array'], 'scheduled_at' => ['sometimes', 'nullable', 'date']]);
        $item = $update->handle($get->forUser($request->user()->getAuthIdentifier()), Publication::query()->findOrFail($publication), $data);

        return response()->json(['data' => $this->resource($item)]);
    }

    public function destroy(string $publication, Request $request, GetProfile $get, DeletePublication $delete): JsonResponse
    {
        $delete->handle($get->forUser($request->user()->getAuthIdentifier()), Publication::query()->findOrFail($publication));

        return response()->json(status: 204);
    }

    public function enrichments(string $publication, Request $request, GetProfile $get, UpdatePublicationEnrichments $update): JsonResponse
    {
        $data = $request->validate(['mentions' => ['sometimes', 'array', 'max:100'], 'mentions.*' => ['string', 'uuid'], 'hashtags' => ['sometimes', 'array', 'max:50'], 'hashtags.*' => ['string', 'max:80'], 'links' => ['sometimes', 'array', 'max:20'], 'links.*.url' => ['required_with:links', 'url', 'max:2048'], 'poll' => ['sometimes', 'array'], 'poll.options' => ['required_with:poll', 'array', 'min:2', 'max:20'], 'poll.options.*' => ['string', 'max:200'], 'poll.closes_at' => ['nullable', 'date']]);
        $item = $update->handle($get->forUser($request->user()->getAuthIdentifier()), Publication::query()->findOrFail($publication), $data);

        return response()->json(['data' => $this->resource($item)]);
    }

    /** @return array<string,mixed> */
    private function resource(Publication $p): array
    {
        return ['id' => $p->getKey(), 'type' => 'social-network-publications', 'kind' => $p->kind, 'state' => $p->state, 'audience' => $p->audience, 'title' => $p->title, 'body' => $p->body, 'metadata' => $p->metadata, 'scheduled_at' => $p->scheduled_at?->toISOString(), 'published_at' => $p->published_at?->toISOString(), 'created_at' => $p->created_at?->toISOString(), 'updated_at' => $p->updated_at?->toISOString()];
    }
}
