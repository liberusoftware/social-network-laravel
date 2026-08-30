<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Actions;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Liberu\SocialNetwork\Media\Models\MediaAsset;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class MarkMediaReady
{
    public function handle(Profile $owner, MediaAsset $asset): MediaAsset
    {
        Gate::authorize('social-network.media.update', [$owner, $asset]);
        abort_unless($asset->owner_profile_id === $owner->getKey(), 404);
        $disk = Storage::disk($asset->disk);
        abort_unless($disk->exists($asset->path), 422, 'The media file is unavailable.');
        $asset->update([
            'state' => 'ready',
            'mime_type' => $disk->mimeType($asset->path) ?: $asset->mime_type,
            'size' => $disk->size($asset->path),
            'checksum' => $disk->checksum($asset->path),
        ]);

        return $asset->refresh();
    }
}
