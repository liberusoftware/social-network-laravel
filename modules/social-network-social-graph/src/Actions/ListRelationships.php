<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Actions;

use Illuminate\Database\Eloquent\Collection;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\SocialGraph\Contracts\GraphAuthorizer;
use Liberu\SocialNetwork\SocialGraph\Models\Relationship;

final readonly class ListRelationships
{
    public function __construct(private GraphAuthorizer $authorizer) {}

    /** @return Collection<int, Relationship> */
    public function handle(Profile $viewer, ?string $type = null, ?string $status = null, int $limit = 100): Collection
    {
        $this->authorizer->list($viewer);

        $query = Relationship::query()
            ->visibleTo((string) $viewer->getKey())
            ->latest()
            ->limit(min(max($limit, 1), (int) config('social-network-social-graph.maximum_relationships', 100)));

        if ($type !== null) {
            $query->where('relationship_type', $type);
        }

        if ($status !== null) {
            $query->where('status', $status);
        }

        return $query->get();
    }
}
