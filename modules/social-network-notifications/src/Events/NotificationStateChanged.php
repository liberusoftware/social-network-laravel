<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\Notifications\Models\SocialNotification;

final readonly class NotificationStateChanged implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    public function __construct(public SocialNotification $notification) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('social-notifications.'.$this->notification->profile_id)];
    }

    public function broadcastAs(): string
    {
        return 'notification.state-changed';
    }

    public function broadcastWith(): array
    {
        return ['notification' => [
            'id' => $this->notification->getKey(),
            'state' => $this->notification->state,
            'read_at' => $this->notification->read_at?->toISOString(),
        ]];
    }
}
