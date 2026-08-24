<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Communities\Contracts;

use Liberu\SocialNetwork\Profiles\Models\Profile;

interface CommunityAuthorizer
{
    public function create(Profile $owner): void;

    public function join(Profile $member): void;
}
