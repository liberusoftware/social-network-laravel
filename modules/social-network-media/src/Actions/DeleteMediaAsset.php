<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Actions;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Liberu\SocialNetwork\Media\Models\MediaAsset;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class DeleteMediaAsset
{
    public function handle(Profile $owner, MediaAsset $asset): void
    {
        Gate::authorize('social-network.media.update', [$owner, $asset]);
        abort_unless((string) $asset->owner_profile_id === (string) $owner->getKey(), 404);
        Storage::disk($asset->disk)->delete($asset->path);
        $asset->delete();
    }
}
