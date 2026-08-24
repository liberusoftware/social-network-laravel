<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialCore\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Liberu\SocialNetwork\SocialCore\Actions\GetSocialNetworkSettings;
use Liberu\SocialNetwork\SocialCore\Actions\UpdateSocialNetworkSettings;

final class SocialCoreSettingsController extends Controller
{
    public function show(Request $request, GetSocialNetworkSettings $get): JsonResponse
    {
        $settings = $get->handle($this->teamId($request));

        return response()->json(['data' => $this->attributes($settings)])
            ->setEtag($this->etag($settings));
    }

    public function update(Request $request, UpdateSocialNetworkSettings $update): JsonResponse
    {
        $teamId = $this->teamId($request);
        $current = app(GetSocialNetworkSettings::class)->handle($teamId);
        $etag = $this->etag($current);

        if ($request->hasHeader('If-Match') && trim($request->header('If-Match')) !== $etag) {
            return response()->json([
                'type' => 'https://www.rfc-editor.org/rfc/rfc9457#section-3.1',
                'title' => 'The resource has changed.',
                'status' => 412,
                'detail' => 'Refresh the Social Core settings before retrying this update.',
            ], 412, ['Content-Type' => 'application/problem+json']);
        }

        $data = $request->validate([
            'deployment_mode' => ['sometimes', 'string', Rule::in((array) config('social-network-social-core.allowed_deployment_modes'))],
            'network_settings' => ['sometimes', 'array', 'max:64'],
            'terminology' => ['sometimes', 'array', 'max:64'],
            'feature_policy' => ['sometimes', 'array', 'max:64'],
            'shared_ids' => ['sometimes', 'array', 'max:64'],
        ]);

        $settings = $update->handle($teamId, $data, $request->user()?->getAuthIdentifier());

        return response()->json(['data' => $this->attributes($settings)])
            ->setEtag($this->etag($settings));
    }

    private function teamId(Request $request): int|string
    {
        $teamId = $request->user()?->current_team_id;
        abort_unless($teamId !== null, 404);

        return $teamId;
    }

    /** @return array<string, mixed> */
    private function attributes(object $settings): array
    {
        return [
            'id' => $settings->getKey(),
            'type' => 'social-network-social-core',
            'deployment_mode' => $settings->deployment_mode,
            'network_settings' => $settings->network_settings,
            'terminology' => $settings->terminology,
            'feature_policy' => $settings->feature_policy,
            'shared_ids' => $settings->shared_ids,
            'created_at' => $settings->created_at?->toISOString(),
            'updated_at' => $settings->updated_at?->toISOString(),
        ];
    }

    private function etag(object $settings): string
    {
        return '"'.sha1((string) ($settings->updated_at?->toISOString() ?? $settings->getKey())).'"';
    }
}
