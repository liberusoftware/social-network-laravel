<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\SocialNetwork\Media\Contracts\MediaAuthorizer;
use Liberu\SocialNetwork\Media\Models\Album;
use Liberu\SocialNetwork\Media\Models\MediaAsset;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class UpdateMediaAsset
{
    public function __construct(private MediaAuthorizer $authorizer) {}

    /** @param array<string, mixed> $attributes */
    public function handle(Profile $owner, MediaAsset $asset, array $attributes): MediaAsset
    {
        $this->authorizer->update($owner);
        abort_unless((string) $asset->owner_profile_id === (string) $owner->getKey(), 403);

        if (array_key_exists('album_id', $attributes) && $attributes['album_id'] !== null && ! Album::query()->whereKey($attributes['album_id'])->where('owner_profile_id', $owner->getKey())->exists()) {
            throw new InvalidArgumentException('The selected album does not belong to the owner.');
        }

        DB::transaction(fn (): bool => $asset->update(array_intersect_key($attributes, array_flip(['album_id', 'alt_text', 'captions', 'rights', 'metadata']))));

        return $asset->refresh();
    }
}
