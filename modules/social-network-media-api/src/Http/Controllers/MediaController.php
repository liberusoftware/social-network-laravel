<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Liberu\SocialNetwork\Media\Actions\CreateAlbum;
use Liberu\SocialNetwork\Media\Actions\DeleteAlbum;
use Liberu\SocialNetwork\Media\Actions\DeleteMediaAsset;
use Liberu\SocialNetwork\Media\Actions\MarkMediaReady;
use Liberu\SocialNetwork\Media\Actions\RegisterMediaAsset;
use Liberu\SocialNetwork\Media\Actions\UpdateAlbum;
use Liberu\SocialNetwork\Media\Actions\UpdateMediaAsset;
use Liberu\SocialNetwork\Media\Models\Album;
use Liberu\SocialNetwork\Media\Models\MediaAsset;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;

final class MediaController extends Controller
{
    public function store(Request $request, GetProfile $get, RegisterMediaAsset $register): JsonResponse
    {
        $data = $request->validate(['type' => ['required', 'in:image,video,audio,file'], 'path' => ['sometimes', 'string', 'max:2048', 'not_regex:#(^/|(^|/)\.\.(/|$)|://)#'], 'file' => ['sometimes', 'file', 'max:51200'], 'disk' => ['sometimes', 'string', 'max:64', Rule::in(array_keys((array) config('filesystems.disks', [])))], 'album_id' => ['nullable', 'uuid'], 'mime_type' => ['nullable', 'string', 'max:160'], 'size' => ['nullable', 'integer', 'min:0'], 'checksum' => ['nullable', 'string', 'max:128'], 'alt_text' => ['nullable', 'string', 'max:1000'], 'captions' => ['nullable', 'string'], 'rights' => ['sometimes', 'array'], 'metadata' => ['sometimes', 'array']]);
        abort_unless($request->filled('path') || $request->hasFile('file'), 422, 'A media path or upload is required.');
        $profile = $get->forUser($request->user()->getAuthIdentifier());
        $disk = (string) ($data['disk'] ?? config('filesystems.default', 'public'));
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $mime = (string) $file->getMimeType();
            $type = $data['type'];
            $validType = match ($type) {
                'image' => Str::startsWith($mime, 'image/'),
                'video' => Str::startsWith($mime, 'video/'),
                'audio' => Str::startsWith($mime, 'audio/'),
                default => true,
            };
            abort_unless($validType, 422, 'The uploaded file does not match the selected media type.');
            $data['path'] = $file->store('social-media/'.$profile->getKey(), $disk);
            $data['mime_type'] = $mime;
            $data['size'] = $file->getSize();
            $data['disk'] = $disk;
        }
        unset($data['file']);
        $asset = $register->handle($profile, $data);

        return response()->json(['data' => ['id' => $asset->getKey(), 'type' => 'social-network-media-assets', 'media_type' => $asset->type, 'state' => $asset->state, 'path' => $asset->path, 'alt_text' => $asset->alt_text]], 201);
    }

    public function index(Request $request, GetProfile $get): JsonResponse
    {
        $data = $request->validate(['limit' => ['sometimes', 'integer', 'min:1', 'max:100']]);
        $items = MediaAsset::query()->where('owner_profile_id', $get->forUser($request->user()->getAuthIdentifier())->getKey())->latest()->limit($data['limit'] ?? 25)->get();

        return response()->json(['data' => $items->map(fn (MediaAsset $asset): array => $this->resource($asset))->values()]);
    }

    public function show(string $asset, Request $request, GetProfile $get): JsonResponse
    {
        $item = MediaAsset::query()->findOrFail($asset);
        abort_unless((string) $item->owner_profile_id === (string) $get->forUser($request->user()->getAuthIdentifier())->getKey(), 403);

        return response()->json(['data' => $this->resource($item)]);
    }

    public function update(string $asset, Request $request, GetProfile $get, UpdateMediaAsset $update): JsonResponse
    {
        $data = $request->validate(['album_id' => ['nullable', 'uuid'], 'alt_text' => ['nullable', 'string', 'max:1000'], 'captions' => ['nullable', 'string'], 'rights' => ['sometimes', 'array'], 'metadata' => ['sometimes', 'array']]);
        $item = $update->handle($get->forUser($request->user()->getAuthIdentifier()), MediaAsset::query()->findOrFail($asset), $data);

        return response()->json(['data' => $this->resource($item)]);
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

    public function albums(Request $request, GetProfile $get): JsonResponse
    {
        $viewer = $get->forUser($request->user()->getAuthIdentifier());
        $data = $request->validate(['limit' => ['sometimes', 'integer', 'min:1', 'max:100']]);
        $albums = Album::query()->visibleTo($viewer)->withCount('media')->latest()->limit($data['limit'] ?? 25)->get();

        return response()->json(['data' => $albums->map(fn (Album $album): array => $this->albumResource($album))->values()]);
    }

    public function storeAlbum(Request $request, GetProfile $get, CreateAlbum $create): JsonResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'description' => ['nullable', 'string', 'max:1000'], 'privacy' => ['sometimes', Rule::in(['public', 'friends_only', 'private'])], 'cover_path' => ['nullable', 'string', 'max:2048']]);
        $album = $create->handle($get->forUser($request->user()->getAuthIdentifier()), $data);

        return response()->json(['data' => $this->albumResource($album)], 201);
    }

    public function showAlbum(string $album, Request $request, GetProfile $get): JsonResponse
    {
        $viewer = $get->forUser($request->user()->getAuthIdentifier());
        $album = Album::query()->findOrFail($album);
        abort_unless($album->privacy === 'public' || (string) $album->owner_profile_id === (string) $viewer->getKey() || Album::query()->whereKey($album->getKey())->where('privacy', 'friends_only')->visibleTo($viewer)->exists(), 403);
        $album->load(['media' => fn ($query) => $query->latest()])->loadCount('media');

        return response()->json(['data' => $this->albumResource($album, true)]);
    }

    public function updateAlbum(string $album, Request $request, GetProfile $get, UpdateAlbum $update): JsonResponse
    {
        $data = $request->validate(['name' => ['sometimes', 'string', 'max:255'], 'description' => ['nullable', 'string', 'max:1000'], 'privacy' => ['sometimes', Rule::in(['public', 'friends_only', 'private'])]]);
        $album = Album::query()->findOrFail($album);
        $updated = $update->handle($get->forUser($request->user()->getAuthIdentifier()), $album, $data);

        return response()->json(['data' => $this->albumResource($updated)]);
    }

    public function destroyAlbum(string $album, Request $request, GetProfile $get, DeleteAlbum $delete): JsonResponse
    {
        $album = Album::query()->findOrFail($album);
        $delete->handle($get->forUser($request->user()->getAuthIdentifier()), $album);

        return response()->json(status: 204);
    }

    private function albumResource(Album $album, bool $includeMedia = false): array
    {
        $resource = ['id' => $album->getKey(), 'type' => 'social-network-media-albums', 'name' => $album->name, 'description' => $album->description, 'privacy' => $album->privacy, 'cover_path' => $album->cover_path, 'media_count' => $album->media_count ?? null];
        if ($includeMedia) {
            $resource['media'] = $album->media->map(fn (MediaAsset $asset): array => $this->resource($asset))->values();
        }

        return $resource;
    }
}
