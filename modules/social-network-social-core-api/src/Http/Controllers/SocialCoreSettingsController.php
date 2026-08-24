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
        return response()->json(['data' => $this->attributes($get->handle($this->teamId($request)))]);
    }

    public function update(Request $request, UpdateSocialNetworkSettings $update): JsonResponse
    {
        $data = $request->validate([
            'deployment_mode' => ['sometimes', 'string', Rule::in((array) config('social-network-social-core.allowed_deployment_modes'))],
            'network_settings' => ['sometimes', 'array'],
            'terminology' => ['sometimes', 'array'],
            'feature_policy' => ['sometimes', 'array'],
            'shared_ids' => ['sometimes', 'array'],
        ]);

        return response()->json(['data' => $this->attributes($update->handle($this->teamId($request), $data))]);
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
}
