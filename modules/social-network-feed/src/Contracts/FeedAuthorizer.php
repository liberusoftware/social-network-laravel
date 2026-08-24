<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Feed\Contracts;

use Liberu\SocialNetwork\Profiles\Models\Profile;

interface FeedAuthorizer
{
    public function view(Profile $viewer): void;
}
