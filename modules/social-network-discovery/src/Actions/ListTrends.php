<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Discovery\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class ListTrends
{
    public function __construct(private \Liberu\SocialNetwork\Discovery\Contracts\DiscoveryAuthorizer $authorizer) {}
    public function handle(Profile $viewer, int $limit = 20): array
    {
        $this->authorizer->search($viewer);
        return DB::table('social_discovery_index')->where('visibility', 'public')->whereNotNull('terms')->orderByDesc('engagement_score')->limit(max(1, min($limit, 100)))->pluck('terms')->map(fn ($terms) => json_decode((string) $terms, true))->flatten()->countBy()->sortDesc()->take($limit)->all();
    }
}
