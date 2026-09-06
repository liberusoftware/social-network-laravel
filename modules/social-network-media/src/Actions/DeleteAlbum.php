<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Actions;

use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Media\Models\Album;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class DeleteAlbum
{
    public function handle(Profile $owner, Album $album): void
    {
        Gate::authorize('social-network.media.album.update', [$owner, $album]);
        $album->media()->update(['album_id' => null]);
        $album->delete();
    }
}
