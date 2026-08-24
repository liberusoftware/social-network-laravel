<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Feed\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\Feed\Models\FeedEntry;

final readonly class FeedEntryAdded implements ShouldDispatchAfterCommit
{
    public function __construct(public FeedEntry $entry) {}
}
