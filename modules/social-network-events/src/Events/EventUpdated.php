<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\Events\Models\Event;

final readonly class EventUpdated implements ShouldDispatchAfterCommit
{
    public function __construct(public Event $event) {}
}
