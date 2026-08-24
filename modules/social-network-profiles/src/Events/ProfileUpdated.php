<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Profiles\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class ProfileUpdated implements ShouldDispatchAfterCommit
{
    public function __construct(public Profile $profile) {}
}
