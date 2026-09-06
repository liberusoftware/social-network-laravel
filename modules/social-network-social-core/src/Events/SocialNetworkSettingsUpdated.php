<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialCore\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\SocialCore\Models\SocialNetworkSettings;

final readonly class SocialNetworkSettingsUpdated implements ShouldDispatchAfterCommit
{
    /**
     * @param  array<string, mixed>  $before
     * @param  array<string, mixed>  $after
     */
    public function __construct(
        public SocialNetworkSettings $settings,
        public array $before = [],
        public array $after = [],
        public int|string|null $actorId = null,
    ) {}
}
