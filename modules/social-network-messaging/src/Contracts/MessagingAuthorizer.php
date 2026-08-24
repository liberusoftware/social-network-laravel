<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Contracts;

use Liberu\SocialNetwork\Profiles\Models\Profile;

interface MessagingAuthorizer
{
    public function create(Profile $actor): void;

    public function send(Profile $actor): void;
}
