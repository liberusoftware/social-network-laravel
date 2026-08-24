<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\Messaging\Models\Message;

final readonly class MessageSent implements ShouldDispatchAfterCommit
{
    public function __construct(public Message $message) {}
}
