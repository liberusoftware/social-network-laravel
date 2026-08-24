<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing\Authorization;

use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\Publishing\Contracts\PublishingAuthorizer;
use Liberu\SocialNetwork\Publishing\Models\Publication;

final class GatePublishingAuthorizer implements PublishingAuthorizer
{
    public function create(Profile $author): void
    {
        Gate::authorize('social-network.publishing.create', [$author]);
    }

    public function update(Profile $author, Publication $publication): void
    {
        Gate::authorize('social-network.publishing.update', [$author, $publication]);
    }

    public function publish(Profile $author, Publication $publication): void
    {
        Gate::authorize('social-network.publishing.publish', [$author, $publication]);
    }
}
