<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\Notifications\Models\SocialNotification;

final readonly class NotificationCreated implements ShouldDispatchAfterCommit
{
    public function __construct(public SocialNotification $notification) {}
}
