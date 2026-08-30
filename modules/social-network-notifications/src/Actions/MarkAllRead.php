<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use Liberu\SocialNetwork\Notifications\Contracts\NotificationAuthorizer;
use Liberu\SocialNetwork\Notifications\Events\NotificationStateChanged;
use Liberu\SocialNetwork\Notifications\Models\SocialNotification;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class MarkAllRead
{
    public function __construct(private NotificationAuthorizer $authorizer, private Dispatcher $events) {}

    public function handle(Profile $profile): int
    {
        $this->authorizer->view($profile);
        $now = now();
        $notifications = SocialNotification::query()->where('profile_id', $profile->getKey())->where('state', 'unread')->get();

        DB::transaction(function () use ($notifications, $now): void {
            foreach ($notifications as $notification) {
                $notification->update(['state' => 'read', 'read_at' => $now]);
                $this->events->dispatch(new NotificationStateChanged($notification->refresh()));
            }
        });

        return $notifications->count();
    }
}
