<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Analytics\Actions;

use Illuminate\Database\Eloquent\Collection;
use Liberu\SocialNetwork\Analytics\Contracts\AnalyticsAuthorizer;
use Liberu\SocialNetwork\Analytics\Models\MetricSnapshot;

final readonly class GetMetrics
{
    public function __construct(private AnalyticsAuthorizer $authorizer) {}

    public function handle(object $actor, string $metric, int $limit = 30): Collection
    {
        $this->authorizer->view($actor, $metric);
        $limit = max(1, min(100, $limit));

        return MetricSnapshot::query()
            ->where('metric', $metric)
            ->where('cohort_size', '>=', (int) config('social-network-analytics.minimum_cohort_size'))
            ->latest('period_end')
            ->limit($limit)
            ->get();
    }
}
