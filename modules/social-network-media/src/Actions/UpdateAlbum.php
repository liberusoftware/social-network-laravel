<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Actions;

use Illuminate\Support\Facades\Gate;
use InvalidArgumentException;
use Liberu\SocialNetwork\Media\Models\Album;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class UpdateAlbum
{
    /** @param array<string, mixed> $attributes */
    public function handle(Profile $owner, Album $album, array $attributes): Album
    {
        Gate::authorize('social-network.media.album.update', [$owner, $album]);

        $data = [];
        if (array_key_exists('name', $attributes)) {
            $data['name'] = trim((string) $attributes['name']);
            if ($data['name'] === '' || mb_strlen($data['name']) > 255) {
                throw new InvalidArgumentException('An album name is required.');
            }
        }
        if (array_key_exists('description', $attributes)) {
            $data['description'] = $attributes['description'];
        }
        if (array_key_exists('privacy', $attributes)) {
            if (! in_array($attributes['privacy'], ['public', 'friends_only', 'private'], true)) {
                throw new InvalidArgumentException('The album privacy is not supported.');
            }
            $data['privacy'] = $attributes['privacy'];
        }

        $album->update($data);

        return $album->refresh();
    }
}
