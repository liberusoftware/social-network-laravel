<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Actions;

use Illuminate\Database\Eloquent\Collection;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\SocialGraph\Contracts\GraphAuthorizer;
use Liberu\SocialNetwork\SocialGraph\Models\GraphList;

final readonly class ListOwnedLists
{
    public function __construct(private GraphAuthorizer $authorizer) {}

    /** @return Collection<int, GraphList> */
    public function handle(Profile $owner): Collection
    {
        $this->authorizer->list($owner);

        return GraphList::query()->where('owner_profile_id', $owner->getKey())->with('profiles')->latest()->get();
    }
}
