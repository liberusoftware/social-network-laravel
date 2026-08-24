<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Profiles\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\SocialNetwork\Profiles\Contracts\ProfileAuthorizer;
use Liberu\SocialNetwork\Profiles\Events\ProfileBlocked;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class BlockProfile
{
    public function __construct(private ProfileAuthorizer $authorizer, private Dispatcher $events) {}

    public function handle(Profile $blocker, Profile $blocked): void
    {
        if ($blocker->is($blocked)) {
            throw new InvalidArgumentException('A profile cannot block itself.');
        }
        $this->authorizer->block($blocker, $blocked);
        DB::transaction(fn () => $blocker->blockedProfiles()->syncWithoutDetaching([$blocked->getKey()]));
        $this->events->dispatch(new ProfileBlocked($blocker, $blocked));
    }
}
