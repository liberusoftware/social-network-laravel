<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications\Contracts;

use Liberu\SocialNetwork\Profiles\Models\Profile;

interface NotificationAuthorizer
{
    public function manage(Profile $profile): void;

    public function view(Profile $profile): void;
}
