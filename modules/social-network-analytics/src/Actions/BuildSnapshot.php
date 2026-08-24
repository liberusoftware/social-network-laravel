<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Analytics\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use Liberu\SocialNetwork\Analytics\Contracts\AnalyticsAuthorizer;
use Liberu\SocialNetwork\Analytics\Events\MetricSnapshotCreated;
use Liberu\SocialNetwork\Analytics\Models\MetricSnapshot;

final readonly class BuildSnapshot
{
    public function __construct(
        private AnalyticsAuthorizer $authorizer,
        private Dispatcher $events,
    ) {}

    /** @param array<string, mixed> $dimensions */
    public function handle(object $actor, string $metric, string $periodStart, string $periodEnd, int $cohortSize, float $value, array $dimensions = []): MetricSnapshot
    {
        $this->authorizer->record($actor, $metric);
        abort_if($cohortSize < (int) config('social-network-analytics.minimum_cohort_size'), 422, 'The cohort is too small to report.');
        $snapshot = DB::transaction(fn (): MetricSnapshot => MetricSnapshot::query()->updateOrCreate(
            ['period_start' => $periodStart, 'period_end' => $periodEnd, 'metric' => $metric],
            ['cohort_size' => $cohortSize, 'value' => $value, 'dimensions' => $this->redact($dimensions)],
        ));
        $this->events->dispatch(new MetricSnapshotCreated($snapshot));
        return $snapshot;
    }

    private function redact(array $dimensions): array
    {
        foreach ((array) config('social-network-analytics.private_fields') as $field) {
            unset($dimensions[$field]);
        }
        return $dimensions;
    }
}
