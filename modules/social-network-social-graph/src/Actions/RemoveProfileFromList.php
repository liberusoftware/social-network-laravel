<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\SocialGraph\Contracts\GraphAuthorizer;
use Liberu\SocialNetwork\SocialGraph\Models\GraphList;

final readonly class RemoveProfileFromList
{
    public function __construct(private GraphAuthorizer $authorizer) {}

    public function handle(Profile $owner, GraphList $list, Profile $profile): GraphList
    {
        $this->authorizer->list($owner);
        abort_unless((string) $list->owner_profile_id === (string) $owner->getKey(), 403);

        DB::transaction(fn () => $list->profiles()->detach($profile->getKey()));

        return $list->load('profiles');
    }
}
