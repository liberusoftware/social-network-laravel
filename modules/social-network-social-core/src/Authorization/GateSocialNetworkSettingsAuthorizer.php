<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialCore\Authorization;

use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\SocialCore\Contracts\SocialNetworkSettingsAuthorizer;

final class GateSocialNetworkSettingsAuthorizer implements SocialNetworkSettingsAuthorizer
{
    public function authorize(int|string $teamId): void
    {
        Gate::authorize('social-network.social-core.update', [$teamId]);
    }
}
