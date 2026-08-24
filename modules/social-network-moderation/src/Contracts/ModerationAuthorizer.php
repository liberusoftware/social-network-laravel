<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Moderation\Contracts;

use Liberu\SocialNetwork\Profiles\Models\Profile;

interface ModerationAuthorizer
{
    public function report(Profile $reporter): void;

    public function decide(Profile $actor): void;
}
