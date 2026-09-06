<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing\Actions;

use Illuminate\Database\Eloquent\Collection;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\Publishing\Models\Publication;

final class ListPublications
{
    public function handle(Profile $viewer, int $limit = 25): Collection
    {
        $limit = max(1, min($limit, 100));

        return Publication::query()->where(function ($query) use ($viewer): void {
            $query->where('audience', 'public')
                ->orWhere('author_profile_id', $viewer->getKey());
        })->latest()->limit($limit)->get();
    }
}
