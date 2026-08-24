<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Actions;

use Illuminate\Database\Eloquent\Collection;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class SuggestProfiles
{
    public function for(Profile $profile, int $limit = 10): Collection
    {
        return Profile::query()->whereKeyNot($profile->getKey())->where('lifecycle_state', 'active')->where('visibility', 'public')->limit(min(max($limit, 1), 50))->get();
    }
}
