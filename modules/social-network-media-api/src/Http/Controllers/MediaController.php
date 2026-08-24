<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Liberu\SocialNetwork\Media\Actions\RegisterMediaAsset;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;

final class MediaController extends Controller
{
    public function store(Request $request, GetProfile $get, RegisterMediaAsset $register): JsonResponse
    {
        $data = $request->validate(['type' => ['required', 'in:image,video,audio,file'], 'path' => ['required', 'string', 'max:2048', 'not_regex:#(^/|(^|/)\.\.(/|$)|://)#'], 'disk' => ['sometimes', 'string', 'max:64', Rule::in(array_keys((array) config('filesystems.disks', [])))], 'mime_type' => ['nullable', 'string', 'max:160'], 'size' => ['nullable', 'integer', 'min:0'], 'checksum' => ['nullable', 'string', 'max:128'], 'alt_text' => ['nullable', 'string', 'max:1000'], 'captions' => ['nullable', 'string'], 'rights' => ['sometimes', 'array'], 'metadata' => ['sometimes', 'array']]);
        $asset = $register->handle($get->forUser($request->user()->getAuthIdentifier()), $data);

        return response()->json(['data' => ['id' => $asset->getKey(), 'type' => 'social-network-media-assets', 'media_type' => $asset->type, 'state' => $asset->state, 'path' => $asset->path, 'alt_text' => $asset->alt_text]], 201);
    }
}
