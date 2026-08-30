<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Engagement\Actions;

use Illuminate\Database\Eloquent\Collection;
use Liberu\SocialNetwork\Engagement\Models\Engagement;

final class ListEngagements
{
    /** @return Collection<int, Engagement> */
    public function handle(string $targetType, string $targetId, ?string $kind = null, int $limit = 50): Collection
    {
        $query = Engagement::query()->where('target_type', $targetType)->where('target_id', $targetId)->latest();

        if ($kind !== null) {
            $query->where('kind', $kind);
        }

        return $query->limit(max(1, min($limit, 100)))->get();
    }

    public function count(string $targetType, string $targetId, ?string $kind = null): int
    {
        $query = Engagement::query()->where('target_type', $targetType)->where('target_id', $targetId);

        if ($kind !== null) {
            $query->where('kind', $kind);
        }

        return $query->count();
    }
}
