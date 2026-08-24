<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\SocialGraph\Models\Relationship;

final readonly class RelationshipCreated implements ShouldDispatchAfterCommit
{
    public function __construct(public Relationship $relationship) {}
}
