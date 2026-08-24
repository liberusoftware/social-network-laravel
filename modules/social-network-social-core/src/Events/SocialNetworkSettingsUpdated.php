<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialCore\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\SocialCore\Models\SocialNetworkSettings;

final readonly class SocialNetworkSettingsUpdated implements ShouldDispatchAfterCommit
{
    public function __construct(public SocialNetworkSettings $settings) {}
}
