<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Events;

use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\Messaging\Models\Message;

final readonly class MessageSent implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    public function __construct(public Message $message) {}

    public function broadcastOn(): array
    {
        return [new PresenceChannel('social-conversations.'.$this->message->conversation_id)];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return ['message' => [
            'id' => $this->message->getKey(),
            'conversation_id' => $this->message->conversation_id,
            'sender_profile_id' => $this->message->sender_profile_id,
            'body' => $this->message->body,
            'attachments' => $this->message->attachments,
            'created_at' => $this->message->created_at?->toISOString(),
        ]];
    }
}
