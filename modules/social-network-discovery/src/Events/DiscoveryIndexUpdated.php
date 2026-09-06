<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Discovery\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\Discovery\Models\DiscoveryIndex;

final readonly class DiscoveryIndexUpdated implements ShouldDispatchAfterCommit
{
    public function __construct(public DiscoveryIndex $index) {}
}
