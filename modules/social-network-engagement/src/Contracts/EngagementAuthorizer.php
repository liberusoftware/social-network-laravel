<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Engagement\Contracts;

use Liberu\SocialNetwork\Profiles\Models\Profile;

interface EngagementAuthorizer
{
    public function create(Profile $actor): void;
}
