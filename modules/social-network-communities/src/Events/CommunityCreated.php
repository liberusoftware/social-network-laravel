<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Communities\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\Communities\Models\Community;

final readonly class CommunityCreated implements ShouldDispatchAfterCommit
{
    public function __construct(public Community $community) {}
}
