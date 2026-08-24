<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\SocialNetwork\Notifications\Contracts\NotificationAuthorizer;
use Liberu\SocialNetwork\Notifications\Models\NotificationPreference;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class UpdatePreferences
{
    public function __construct(private NotificationAuthorizer $authorizer) {}

    /** @param array<string,mixed> $data */
    public function handle(Profile $profile, array $data): NotificationPreference
    {
        $this->authorizer->manage($profile);

        return DB::transaction(fn (): NotificationPreference => NotificationPreference::query()->updateOrCreate(['profile_id' => $profile->getKey()], ['channels' => $data['channels'] ?? [], 'quiet_hours' => $data['quiet_hours'] ?? [], 'digest' => $data['digest'] ?? []]));
    }
}
