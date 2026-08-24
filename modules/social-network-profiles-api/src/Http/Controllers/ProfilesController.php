<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Profiles\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Liberu\SocialNetwork\Profiles\Actions\BlockProfile;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\Profiles\Actions\UpdateProfile;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class ProfilesController extends Controller
{
    public function me(Request $request, GetProfile $get): JsonResponse
    {
        return response()->json(['data' => $this->resource($get->forUser($this->userId($request)))]);
    }

    public function show(string $profile, GetProfile $get): JsonResponse
    {
        return response()->json(['data' => $this->resource($get->byId($profile))]);
    }

    public function update(Request $request, GetProfile $get, UpdateProfile $update): JsonResponse
    {
        $data = $request->validate([
            'handle' => ['sometimes', 'string', 'min:3', 'max:32', 'regex:/^[A-Za-z0-9_]+$/'],
            'bio' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'attributes' => ['sometimes', 'array'],
            'avatar_path' => ['sometimes', 'nullable', 'string', 'max:2048'],
            'visibility' => ['sometimes', Rule::in((array) config('social-network-profiles.visibilities'))],
        ]);
        $profile = $update->handle($get->forUser($this->userId($request)), $data);

        return response()->json(['data' => $this->resource($profile)]);
    }

    public function block(string $profile, Request $request, GetProfile $get, BlockProfile $block): JsonResponse
    {
        $block->handle($get->forUser($this->userId($request)), $get->byId($profile));

        return response()->json(status: 204);
    }

    private function userId(Request $request): int|string
    {
        return $request->user()->getAuthIdentifier();
    }

    /** @return array<string, mixed> */
    private function resource(Profile $profile): array
    {
        return [
            'id' => $profile->getKey(),
            'type' => 'social-network-profiles',
            'handle' => $profile->handle,
            'bio' => $profile->bio,
            'attributes' => $profile->attributes,
            'avatar_path' => $profile->avatar_path,
            'verification' => $profile->verification_status,
            'visibility' => $profile->visibility,
            'lifecycle_state' => $profile->lifecycle_state,
            'created_at' => $profile->created_at?->toISOString(),
            'updated_at' => $profile->updated_at?->toISOString(),
        ];
    }
}
