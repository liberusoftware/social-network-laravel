<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialCore\Repositories;

use Illuminate\Support\Str;
use Liberu\SocialNetwork\SocialCore\Contracts\SocialNetworkSettingsRepository;
use Liberu\SocialNetwork\SocialCore\Models\SocialNetworkSettings;

final class EloquentSocialNetworkSettingsRepository implements SocialNetworkSettingsRepository
{
    public function forTeam(int|string $teamId): SocialNetworkSettings
    {
        return SocialNetworkSettings::query()->firstOrCreate(
            ['team_id' => $teamId],
            [
                'id' => (string) Str::uuid(),
                'deployment_mode' => (string) config('social-network-social-core.default_deployment_mode', 'hosted'),
                'network_settings' => [],
                'terminology' => [],
                'feature_policy' => [],
                'shared_ids' => [],
            ],
        );
    }
}
