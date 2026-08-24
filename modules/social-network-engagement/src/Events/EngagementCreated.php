<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Engagement\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\Engagement\Models\Engagement;

final readonly class EngagementCreated implements ShouldDispatchAfterCommit
{
    public function __construct(public Engagement $engagement) {}
}
