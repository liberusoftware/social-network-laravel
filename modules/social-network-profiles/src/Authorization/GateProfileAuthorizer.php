<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Profiles\Authorization;

use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Profiles\Contracts\ProfileAuthorizer;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class GateProfileAuthorizer implements ProfileAuthorizer
{
    public function view(Profile $profile): void
    {
        Gate::authorize('social-network.profiles.view', [$profile]);
    }

    public function update(Profile $profile): void
    {
        Gate::authorize('social-network.profiles.update', [$profile]);
    }

    public function block(Profile $profile, Profile $target): void
    {
        Gate::authorize('social-network.profiles.block', [$profile, $target]);
    }
}
