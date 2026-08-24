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

        return response()->json([
            'data' => $metrics->handle($request->user(), $metric, $data['limit'] ?? 30),
        ]);
    }
}
