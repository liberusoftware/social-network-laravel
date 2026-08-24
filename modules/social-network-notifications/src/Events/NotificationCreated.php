<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\Notifications\Models\SocialNotification;

final readonly class NotificationCreated implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    public function __construct(public SocialNotification $notification) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('social-notifications.'.$this->notification->profile_id)];
    }

    public function broadcastAs(): string
    {
        return 'notification.created';
    }

    public function broadcastWith(): array
    {
        return ['notification' => [
            'id' => $this->notification->getKey(),
            'kind' => $this->notification->kind,
            'state' => $this->notification->state,
            'channel' => $this->notification->channel,
            'payload' => $this->notification->payload,
        ]];
    }
}
