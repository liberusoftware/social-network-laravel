<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialCore\Actions;

use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\SocialCore\Contracts\SocialNetworkSettingsRepository;
use Liberu\SocialNetwork\SocialCore\Models\SocialNetworkSettings;

final readonly class GetSocialNetworkSettings
{
    public function __construct(private SocialNetworkSettingsRepository $settings) {}

    public function handle(int|string $teamId): SocialNetworkSettings
    {
        Gate::authorize('social-network.social-core.view', [$teamId]);

        return $this->settings->forTeam($teamId);
    }
}
