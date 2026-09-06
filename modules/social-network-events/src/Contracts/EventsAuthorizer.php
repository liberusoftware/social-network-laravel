<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Contracts;

use Liberu\SocialNetwork\Events\Models\Event;
use Liberu\SocialNetwork\Profiles\Models\Profile;

interface EventsAuthorizer
{
    public function create(Profile $owner): void;

    public function manage(Profile $owner, Event $event): void;

    public function attend(Profile $profile, Event $event): void;
}
