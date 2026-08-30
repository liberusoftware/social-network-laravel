<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Moderation\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\Moderation\Models\ModerationDecision;

final readonly class ReportDecided implements ShouldDispatchAfterCommit
{
    public function __construct(public ModerationDecision $decision) {}
}
