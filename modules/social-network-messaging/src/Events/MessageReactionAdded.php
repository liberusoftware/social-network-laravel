<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Events;

use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\Messaging\Models\MessageReaction;

final readonly class MessageReactionAdded implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    public function __construct(public MessageReaction $reaction) {}

    public function broadcastOn(): array
    {
        return [new PresenceChannel('social-conversations.'.$this->reaction->conversation_id)];
    }

    public function broadcastAs(): string
    {
        return 'message.reaction-added';
    }

    public function broadcastWith(): array
    {
        return ['reaction' => $this->reaction->only(['id', 'message_id', 'profile_id', 'emoji'])];
    }
}
