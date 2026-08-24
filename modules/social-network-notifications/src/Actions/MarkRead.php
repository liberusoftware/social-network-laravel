<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications\Actions;

use Liberu\SocialNetwork\Notifications\Contracts\NotificationAuthorizer;
use Liberu\SocialNetwork\Notifications\Models\SocialNotification;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class MarkRead
{
    public function __construct(private NotificationAuthorizer $authorizer) {}

    public function handle(Profile $profile, string $id): SocialNotification
    {
        $this->authorizer->view($profile);
        $notification = SocialNotification::query()->where('profile_id', $profile->getKey())->findOrFail($id);
        $notification->update(['state' => 'read', 'read_at' => now()]);

        return $notification->refresh();
    }
}
