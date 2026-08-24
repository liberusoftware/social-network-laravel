<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Liberu\SocialNetwork\Media\Contracts\MediaAuthorizer;
use Liberu\SocialNetwork\Media\Events\MediaAssetCreated;
use Liberu\SocialNetwork\Media\Models\MediaAsset;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class RegisterMediaAsset
{
    public function __construct(private MediaAuthorizer $authorizer, private Dispatcher $events) {}

    /** @param array<string, mixed> $attributes */
    public function handle(Profile $owner, array $attributes): MediaAsset
    {
        $this->authorizer->upload($owner);
        $type = (string) ($attributes['type'] ?? '');
        $path = trim((string) ($attributes['path'] ?? ''));
        $alt = $attributes['alt_text'] ?? null;
        if (! in_array($type, (array) config('social-network-media.types'), true) || $path === '') {
            throw new InvalidArgumentException('A supported media type and path are required.');
        }
        if ($alt !== null && mb_strlen((string) $alt) > (int) config('social-network-media.max_alt_text_length')) {
            throw new InvalidArgumentException('Alt text is too long.');
        }
        $asset = DB::transaction(fn (): MediaAsset => MediaAsset::query()->create(['id' => (string) Str::uuid(), 'owner_profile_id' => $owner->getKey(), 'type' => $type, 'state' => 'pending', 'disk' => (string) ($attributes['disk'] ?? 'public'), 'path' => $path, 'mime_type' => $attributes['mime_type'] ?? null, 'size' => $attributes['size'] ?? null, 'checksum' => $attributes['checksum'] ?? null, 'alt_text' => $alt, 'captions' => $attributes['captions'] ?? null, 'rights' => $attributes['rights'] ?? [], 'metadata' => $attributes['metadata'] ?? []]));
        $this->events->dispatch(new MediaAssetCreated($asset));

        return $asset;
    }
}
