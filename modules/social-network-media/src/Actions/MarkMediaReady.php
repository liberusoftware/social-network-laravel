<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Actions;

use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Media\Models\MediaAsset;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class MarkMediaReady
{
    public function handle(Profile $owner, MediaAsset $asset): MediaAsset
    {
        Gate::authorize('social-network.media.update', [$owner, $asset]);
        abort_unless($asset->owner_profile_id === $owner->getKey(), 404);
        $asset->update(['state' => 'ready']);

        return $asset->refresh();
    }
}
