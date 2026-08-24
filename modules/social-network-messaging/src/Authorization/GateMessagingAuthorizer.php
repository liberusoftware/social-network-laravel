<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Authorization;

use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Messaging\Contracts\MessagingAuthorizer;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class GateMessagingAuthorizer implements MessagingAuthorizer
{
    public function create(Profile $actor): void
    {
        Gate::authorize('social-network.messaging.create', [$actor]);
    }

    public function send(Profile $actor): void
    {
        Gate::authorize('social-network.messaging.send', [$actor]);
    }
}
