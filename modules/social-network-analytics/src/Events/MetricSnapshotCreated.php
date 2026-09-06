<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Analytics\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\Analytics\Models\MetricSnapshot;

final readonly class MetricSnapshotCreated implements ShouldDispatchAfterCommit
{
    public function __construct(public MetricSnapshot $snapshot) {}
}
