<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Authorization;

use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\SocialGraph\Contracts\GraphAuthorizer;
use Liberu\SocialNetwork\SocialGraph\Models\Relationship;

final class GateGraphAuthorizer implements GraphAuthorizer
{
    public function follow(Profile $s, Profile $t): void
    {
        Gate::authorize('social-network.social-graph.follow', [$s, $t]);
    }

    public function friend(Profile $s, Profile $t): void
    {
        Gate::authorize('social-network.social-graph.friend', [$s, $t]);
    }

    public function list(Profile $o): void
    {
        Gate::authorize('social-network.social-graph.list', [$o]);
    }

    public function block(Profile $s, Profile $t): void
    {
        Gate::authorize('social-network.social-graph.block', [$s, $t]);
    }

    public function unblock(Profile $s, Profile $t): void
    {
        Gate::authorize('social-network.social-graph.block', [$s, $t]);
    }

    public function visibility(Profile $actor, Relationship $relationship): void
    {
        Gate::authorize('social-network.social-graph.visibility', [$actor, $relationship]);
    }
}
