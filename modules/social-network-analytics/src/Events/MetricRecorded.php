<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Analytics\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\Analytics\Models\AnalyticsEvent;

final readonly class MetricRecorded implements ShouldDispatchAfterCommit
{
    public function __construct(public AnalyticsEvent $event) {}
}
