<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Communities\Authorization;

use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Communities\Contracts\CommunityAuthorizer;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class GateCommunityAuthorizer implements CommunityAuthorizer
{
    public function create(Profile $owner): void
    {
        Gate::authorize('social-network.communities.create', [$owner]);
    }

    public function join(Profile $member): void
    {
        Gate::authorize('social-network.communities.join', [$member]);
    }
}
