<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Communities\Actions;

use Illuminate\Database\Eloquent\Collection;
use Liberu\SocialNetwork\Communities\Models\Community;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class ListCommunities
{
    public function handle(Profile $viewer, int $limit = 25): Collection
    {
        return Community::query()->where('visibility', 'public')->orWhere('owner_profile_id', $viewer->getKey())->latest()->limit(max(1, min($limit, 100)))->get();
    }
}
