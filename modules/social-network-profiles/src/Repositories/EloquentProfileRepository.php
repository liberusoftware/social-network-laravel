<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Profiles\Repositories;

use Illuminate\Support\Str;
use Liberu\SocialNetwork\Profiles\Contracts\ProfileRepository;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class EloquentProfileRepository implements ProfileRepository
{
    public function forUser(int|string $userId): Profile
    {
        return Profile::query()->firstOrCreate(
            ['user_id' => $userId],
            ['id' => (string) Str::uuid(), 'handle' => 'user-'.$userId, 'visibility' => config('social-network-profiles.default_visibility')],
        );
    }

    public function find(string $id): Profile
    {
        return Profile::query()->findOrFail($id);
    }
}
