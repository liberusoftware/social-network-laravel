<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Discovery\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Liberu\SocialNetwork\Discovery\Contracts\DiscoveryAuthorizer;
use Liberu\SocialNetwork\Discovery\Events\DiscoveryIndexUpdated;
use Liberu\SocialNetwork\Discovery\Models\DiscoveryIndex;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class IndexResource
{
    public function __construct(private DiscoveryAuthorizer $authorizer, private Dispatcher $events) {}

    /** @param array<string,mixed> $attributes */
    public function handle(Profile $owner, array $attributes): DiscoveryIndex
    {
        $this->authorizer->index($owner);
        $type = trim((string) ($attributes['resource_type'] ?? ''));
        $id = (string) ($attributes['resource_id'] ?? '');
        $body = trim((string) ($attributes['body'] ?? ''));
        $visibility = (string) ($attributes['visibility'] ?? 'public');
        if ($type === '' || ! Str::isUuid($id) || $body === '' || mb_strlen($body) > (int) config('social-network-discovery.max_text_length') || ! in_array($visibility, (array) config('social-network-discovery.visibility_states'), true)) throw new InvalidArgumentException('The discovery index payload is invalid.');
        $index = DB::transaction(fn (): DiscoveryIndex => DiscoveryIndex::query()->updateOrCreate(['resource_type' => $type, 'resource_id' => $id], ['id' => (string) Str::uuid(), 'owner_profile_id' => $owner->getKey(), 'visibility' => $visibility, 'body' => $body, 'terms' => $attributes['terms'] ?? [], 'engagement_score' => max(0, (int) ($attributes['engagement_score'] ?? 0)), 'published_at' => $attributes['published_at'] ?? now()]));
        $this->events->dispatch(new DiscoveryIndexUpdated($index));
        return $index;
    }
}
