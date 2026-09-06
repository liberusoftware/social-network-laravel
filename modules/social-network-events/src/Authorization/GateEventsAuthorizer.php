<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Authorization;

use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Events\Contracts\EventsAuthorizer;
use Liberu\SocialNetwork\Events\Models\Event;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class GateEventsAuthorizer implements EventsAuthorizer
{
    public function create(Profile $owner): void
    {
        Gate::authorize('social-network.events.create', [$owner]);
    }

    public function manage(Profile $owner, Event $event): void
    {
        Gate::authorize('social-network.events.manage', [$owner, $event]);
    }

    public function attend(Profile $profile, Event $event): void
    {
        Gate::authorize('social-network.events.attend', [$profile, $event]);
    }
}
