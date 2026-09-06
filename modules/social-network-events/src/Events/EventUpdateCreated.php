<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\Events\Models\EventUpdate;

final readonly class EventUpdateCreated implements ShouldDispatchAfterCommit
{
    public function __construct(public EventUpdate $update) {}
}
