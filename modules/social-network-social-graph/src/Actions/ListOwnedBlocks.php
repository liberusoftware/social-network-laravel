<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Actions;

use Illuminate\Database\Eloquent\Collection;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\SocialGraph\Contracts\GraphAuthorizer;
use Liberu\SocialNetwork\SocialGraph\Models\Block;

final readonly class ListOwnedBlocks
{
    public function __construct(private GraphAuthorizer $authorizer) {}

    /** @return Collection<int, Block> */
    public function handle(Profile $owner): Collection
    {
        $this->authorizer->list($owner);

        return Block::query()->where('source_profile_id', $owner->getKey())->latest()->get();
    }
}
