<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Liberu\SocialNetwork\Media\Models\MediaAsset;

final readonly class MediaAssetCreated implements ShouldDispatchAfterCommit
{
    public function __construct(public MediaAsset $asset) {}
}
