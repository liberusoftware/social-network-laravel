<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Moderation\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\Moderation\Models\ModerationReport;

final readonly class ReportCreated implements ShouldDispatchAfterCommit
{
    public function __construct(public ModerationReport $report) {}
}
