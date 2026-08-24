<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Discovery\Actions;

use Illuminate\Database\Eloquent\Collection;
use Liberu\SocialNetwork\Discovery\Contracts\DiscoveryAuthorizer;
use Liberu\SocialNetwork\Discovery\Models\DiscoveryIndex;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class SearchDiscovery
{
    public function __construct(private DiscoveryAuthorizer $authorizer) {}

    public function handle(Profile $viewer, string $query, int $limit = 25): Collection
    {
        $this->authorizer->search($viewer);
        $query = trim($query);
        if ($query === '') return new Collection();
        $limit = max(1, min($limit, (int) config('social-network-discovery.max_page_size')));
        return DiscoveryIndex::query()->where('body', 'like', '%'.$query.'%')->where(function ($q) use ($viewer): void { $q->where('visibility', 'public')->orWhere('owner_profile_id', $viewer->getKey()); })->orderByDesc('engagement_score')->latest()->limit($limit)->get();
    }
}
