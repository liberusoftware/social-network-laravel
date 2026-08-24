<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Liberu\SocialNetwork\Media\Actions\RegisterMediaAsset;
use Liberu\SocialNetwork\Media\Actions\DeleteMediaAsset;
use Liberu\SocialNetwork\Media\Actions\MarkMediaReady;
use Liberu\SocialNetwork\Media\Models\MediaAsset;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;

final class MediaController extends Controller
{
    public function store(Request $request, GetProfile $get, RegisterMediaAsset $register): JsonResponse
    {
        $data = $request->validate(['type' => ['required', 'in:image,video,audio,file'], 'path' => ['required', 'string', 'max:2048', 'not_regex:#(^/|(^|/)\.\.(/|$)|://)#'], 'disk' => ['sometimes', 'string', 'max:64', Rule::in(array_keys((array) config('filesystems.disks', [])))], 'mime_type' => ['nullable', 'string', 'max:160'], 'size' => ['nullable', 'integer', 'min:0'], 'checksum' => ['nullable', 'string', 'max:128'], 'alt_text' => ['nullable', 'string', 'max:1000'], 'captions' => ['nullable', 'string'], 'rights' => ['sometimes', 'array'], 'metadata' => ['sometimes', 'array']]);
        $asset = $register->handle($get->forUser($request->user()->getAuthIdentifier()), $data);

        return response()->json(['data' => ['id' => $asset->getKey(), 'type' => 'social-network-media-assets', 'media_type' => $asset->type, 'state' => $asset->state, 'path' => $asset->path, 'alt_text' => $asset->alt_text]], 201);
    }

    public function index(Request $request, GetProfile $get): JsonResponse
    {
        $data = $request->validate(['limit' => ['sometimes', 'integer', 'min:1', 'max:100']]);
        $items = MediaAsset::query()->where('owner_profile_id', $get->forUser($request->user()->getAuthIdentifier())->getKey())->latest()->limit($data['limit'] ?? 25)->get();
        return response()->json(['data' => $items->map(fn (MediaAsset $asset): array => $this->resource($asset))->values()]);
    }

    public function ready(string $asset, Request $request, GetProfile $get, MarkMediaReady $ready): JsonResponse
    {
        return response()->json(['data' => $this->resource($ready->handle($get->forUser($request->user()->getAuthIdentifier()), MediaAsset::query()->findOrFail($asset)))]);
    }

    public function destroy(string $asset, Request $request, GetProfile $get, DeleteMediaAsset $delete): JsonResponse
    {
        $delete->handle($get->forUser($request->user()->getAuthIdentifier()), MediaAsset::query()->findOrFail($asset));
        return response()->json(status: 204);
    }

    private function resource(MediaAsset $asset): array
    {
        return ['id' => $asset->getKey(), 'type' => 'social-network-media-assets', 'media_type' => $asset->type, 'state' => $asset->state, 'path' => $asset->path, 'alt_text' => $asset->alt_text, 'mime_type' => $asset->mime_type, 'size' => $asset->size];
    }
}
