<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Engagement\Authorization;

use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Engagement\Contracts\EngagementAuthorizer;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class GateEngagementAuthorizer implements EngagementAuthorizer
{
    public function create(Profile $actor): void
    {
        Gate::authorize('social-network.engagement.create', [$actor]);
    }
}
