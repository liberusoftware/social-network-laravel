<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Events;

use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

final readonly class UserTyping implements ShouldBroadcast
{
    public function __construct(
        public string $conversationId,
        public string $profileId,
    ) {}

    public function broadcastOn(): array
    {
        return [new PresenceChannel('social-conversations.'.$this->conversationId)];
    }

    public function broadcastAs(): string
    {
        return 'message.typing';
    }

    public function broadcastWith(): array
    {
        return ['conversation_id' => $this->conversationId, 'profile_id' => $this->profileId];
    }
}
