<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Moderation\Authorization;

use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Moderation\Contracts\ModerationAuthorizer;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class GateModerationAuthorizer implements ModerationAuthorizer
{
    public function report(Profile $reporter): void
    {
        Gate::authorize('social-network.moderation.report', [$reporter]);
    }

    public function decide(Profile $actor): void
    {
        Gate::authorize('social-network.moderation.decide', [$actor]);
    }
}
