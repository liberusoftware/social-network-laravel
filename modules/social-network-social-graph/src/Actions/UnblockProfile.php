<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\SocialGraph\Contracts\GraphAuthorizer;

final readonly class UnblockProfile
{
    public function __construct(private GraphAuthorizer $authorizer) {}

    public function handle(Profile $source, Profile $target): bool
    {
        $this->authorizer->unblock($source, $target);

        return (bool) DB::table('social_graph_blocks')
            ->where('source_profile_id', $source->getKey())
            ->where('target_profile_id', $target->getKey())
            ->delete();
    }
}
