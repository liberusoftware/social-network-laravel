<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialCore\Contracts;

use Liberu\SocialNetwork\SocialCore\Models\SocialNetworkSettings;

interface SocialNetworkSettingsRepository
{
    public function forTeam(int|string $teamId): SocialNetworkSettings;
}
