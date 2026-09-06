<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\Publishing\Models\Publication;

final readonly class PublicationUpdated implements ShouldDispatchAfterCommit
{
    public function __construct(public Publication $publication) {}
}
