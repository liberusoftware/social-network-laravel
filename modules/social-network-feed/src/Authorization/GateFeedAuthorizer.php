<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Feed\Authorization;

use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Feed\Contracts\FeedAuthorizer;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class GateFeedAuthorizer implements FeedAuthorizer
{
    public function view(Profile $viewer): void
    {
        Gate::authorize('social-network.feed.view', [$viewer]);
    }
}
