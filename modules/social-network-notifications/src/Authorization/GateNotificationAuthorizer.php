<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications\Authorization;

use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Notifications\Contracts\NotificationAuthorizer;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class GateNotificationAuthorizer implements NotificationAuthorizer
{
    public function manage(Profile $profile): void
    {
        Gate::authorize('social-network.notifications.manage', [$profile]);
    }

    public function view(Profile $profile): void
    {
        Gate::authorize('social-network.notifications.view', [$profile]);
    }
}
