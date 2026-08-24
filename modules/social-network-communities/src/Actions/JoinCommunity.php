<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Communities\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\SocialNetwork\Communities\Contracts\CommunityAuthorizer;
use Liberu\SocialNetwork\Communities\Models\Community;
use Liberu\SocialNetwork\Communities\Models\Membership;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class JoinCommunity
{
    public function __construct(private CommunityAuthorizer $authorizer) {}

    public function handle(Profile $member, Community $community): Membership
    {
        $this->authorizer->join($member);

        return DB::transaction(fn (): Membership => Membership::query()->updateOrCreate(['community_id' => $community->getKey(), 'profile_id' => $member->getKey()], ['role' => 'member', 'status' => $community->visibility === 'public' ? 'active' : 'pending']));
    }
}
