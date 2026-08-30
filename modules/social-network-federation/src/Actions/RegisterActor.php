<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Federation\Actions;

use Illuminate\Support\Str;
use Liberu\SocialNetwork\Federation\Models\FederationActor;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class RegisterActor
{
    /** @param array<string, mixed> $data */
    public function handle(Profile $profile, array $data): FederationActor
    {
        return FederationActor::query()->updateOrCreate(
            ['profile_id' => $profile->getKey()],
            [
                'id' => (string) Str::uuid(),
                'handle' => trim((string) $data['handle']),
                'actor_url' => trim((string) $data['actor_url']),
                'inbox_url' => trim((string) $data['inbox_url']),
                'outbox_url' => isset($data['outbox_url']) ? trim((string) $data['outbox_url']) : null,
                'public_key' => $data['public_key'] ?? null,
                'state' => 'active',
            ],
        );
    }
}
