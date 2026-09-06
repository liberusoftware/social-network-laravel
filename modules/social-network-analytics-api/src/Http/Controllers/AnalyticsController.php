<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Analytics\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\SocialNetwork\Analytics\Actions\GetMetrics;

final class AnalyticsController extends Controller
{
    public function show(Request $request, string $metric, GetMetrics $metrics): JsonResponse
    {
        abort_unless(preg_match('/^[a-z0-9_.-]{1,120}$/', $metric) === 1, 404);
        $data = $request->validate(['limit' => ['sometimes', 'integer', 'min:1', 'max:100']]);

        return response()->json(['data' => $metrics->handle($request->user(), $metric, $data['limit'] ?? 30)->map(
            fn ($snapshot): array => [
                'id' => (string) $snapshot->getKey(),
                'type' => 'social-network-analytics-snapshot',
                'metric' => $snapshot->metric,
                'period_start' => $snapshot->period_start?->toDateString(),
                'period_end' => $snapshot->period_end?->toDateString(),
                'cohort_size' => $snapshot->cohort_size,
                'value' => (float) $snapshot->value,
                'dimensions' => $snapshot->dimensions,
            ],
        )->values()]);
    }
}
