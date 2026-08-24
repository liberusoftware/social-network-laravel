<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Profiles\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use Liberu\SocialNetwork\Profiles\Contracts\ProfileAuthorizer;
use Liberu\SocialNetwork\Profiles\Events\ProfileUnblocked;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class UnblockProfile
{
    public function __construct(private ProfileAuthorizer $authorizer, private Dispatcher $events) {}

    public function handle(Profile $blocker, Profile $blocked): void
    {
        $this->authorizer->block($blocker, $blocked);
        DB::table('social_profile_blocks')
            ->where('blocker_profile_id', $blocker->getKey())
            ->where('blocked_profile_id', $blocked->getKey())
            ->delete();
        $this->events->dispatch(new ProfileUnblocked($blocker, $blocked));
    }
}
