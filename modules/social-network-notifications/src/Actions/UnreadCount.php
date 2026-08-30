<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications\Actions;

use Liberu\SocialNetwork\Notifications\Contracts\NotificationAuthorizer;
use Liberu\SocialNetwork\Notifications\Models\SocialNotification;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class UnreadCount
{
    public function __construct(private NotificationAuthorizer $authorizer) {}

    public function handle(Profile $profile): int
    {
        $this->authorizer->view($profile);

        return SocialNotification::query()->where('profile_id', $profile->getKey())->where('state', 'unread')->count();
    }
}
