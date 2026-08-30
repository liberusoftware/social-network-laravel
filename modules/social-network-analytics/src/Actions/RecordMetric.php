<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Analytics\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\SocialNetwork\Analytics\Contracts\AnalyticsAuthorizer;
use Liberu\SocialNetwork\Analytics\Events\MetricRecorded;
use Liberu\SocialNetwork\Analytics\Models\AnalyticsEvent;

final readonly class RecordMetric
{
    public function __construct(
        private AnalyticsAuthorizer $authorizer,
        private Dispatcher $events,
    ) {}

    /** @param array<string, mixed> $dimensions */
    public function handle(object $actor, string $name, array $dimensions = [], int $value = 1): AnalyticsEvent
    {
        $name = $this->metricName($name);
        $this->authorizer->record($actor, $name);
        $dimensions = $this->redact($dimensions);

        if (count($dimensions) > (int) config('social-network-analytics.maximum_dimensions', 32)) {
            throw new InvalidArgumentException('Metric dimensions exceed the configured limit.');
        }

        $event = DB::transaction(fn (): AnalyticsEvent => AnalyticsEvent::query()->create([
            'name' => $name,
            'occurred_on' => now()->toDateString(),
            'dimensions' => $dimensions,
            'value' => max(0, $value),
        ]));

        $this->events->dispatch(new MetricRecorded($event));

        return $event;
    }

    private function metricName(string $name): string
    {
        $name = trim($name);

        if (preg_match('/^[a-z0-9][a-z0-9_.-]{0,119}$/', $name) !== 1) {
            throw new InvalidArgumentException('Metric names must use lowercase letters, numbers, dots, dashes, or underscores.');
        }

        return $name;
    }

    /** @param array<string, mixed> $dimensions */
    private function redact(array $dimensions): array
    {
        $privateFields = array_map('strval', (array) config('social-network-analytics.private_fields'));

        foreach ($dimensions as $key => $value) {
            if (in_array((string) $key, $privateFields, true)) {
                unset($dimensions[$key]);
            } elseif (is_array($value)) {
                $dimensions[$key] = $this->redact($value);
            }
        }

        return $dimensions;
    }
}
