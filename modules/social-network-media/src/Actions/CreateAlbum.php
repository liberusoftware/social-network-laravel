<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Actions;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Liberu\SocialNetwork\Media\Models\Album;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class CreateAlbum
{
    /** @param array<string, mixed> $attributes */
    public function handle(Profile $owner, array $attributes): Album
    {
        Gate::authorize('social-network.media.album.create', [$owner]);

        $attributes = $this->validatedAttributes($attributes);

        return Album::query()->create([
            'id' => (string) Str::uuid(),
            'owner_profile_id' => $owner->getKey(),
            ...$attributes,
        ]);
    }

    /** @param array<string, mixed> $attributes @return array<string, mixed> */
    private function validatedAttributes(array $attributes): array
    {
        $name = trim((string) ($attributes['name'] ?? ''));
        $privacy = (string) ($attributes['privacy'] ?? 'private');

        if ($name === '' || mb_strlen($name) > 255) {
            throw new InvalidArgumentException('An album name is required.');
        }

        if (! in_array($privacy, ['public', 'friends_only', 'private'], true)) {
            throw new InvalidArgumentException('The album privacy is not supported.');
        }

        return [
            'name' => $name,
            'description' => $attributes['description'] ?? null,
            'privacy' => $privacy,
            'cover_path' => $attributes['cover_path'] ?? null,
        ];
    }
}
