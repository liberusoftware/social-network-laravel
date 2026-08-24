<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Liberu\SocialNetwork\Notifications\Events\NotificationCreated;
use Liberu\SocialNetwork\Notifications\Models\SocialNotification;

final readonly class CreateNotification
{
    public function __construct(private Dispatcher $events) {}

    /** @param array<string,mixed> $payload */
    public function handle(string $profileId, string $kind, array $payload = [], string $channel = 'in_app', ?string $groupKey = null): SocialNotification
    {
        if ($kind === '' || strlen($kind) > 120 || ! in_array($channel, (array) config('social-network-notifications.channels'), true)) {
            throw new InvalidArgumentException('The notification kind or channel is not supported.');
        }
        if (count($payload) > 64) {
            throw new InvalidArgumentException('Notification payload exceeds the configured limit.');
        }
        $notification = DB::transaction(fn (): SocialNotification => SocialNotification::query()->create(['id' => (string) Str::uuid(), 'profile_id' => $profileId, 'kind' => $kind, 'group_key' => $groupKey, 'channel' => $channel, 'state' => 'unread', 'payload' => $payload]));
        $this->events->dispatch(new NotificationCreated($notification));

        return $notification;
    }
}
