<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Moderation\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\SocialNetwork\Moderation\Actions\CreateReport;
use Liberu\SocialNetwork\Moderation\Actions\DecideReport;
use Liberu\SocialNetwork\Moderation\Models\ModerationReport;
use Illuminate\Validation\Rule;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;

final class ModerationController extends Controller
{
    public function report(Request $request, GetProfile $get, CreateReport $create): JsonResponse
    {
        $data = $request->validate(['target_type' => ['required', 'string', 'max:160'], 'target_id' => ['required', 'uuid'], 'reason' => ['required', 'string', 'max:120'], 'details' => ['nullable', 'string', 'max:10000']]);
        $report = $create->handle($get->forUser($request->user()->getAuthIdentifier()), $data['target_type'], $data['target_id'], $data['reason'], $data['details'] ?? null);

        return response()->json(['data' => ['id' => $report->getKey(), 'type' => 'social-network-moderation-reports', 'state' => $report->state, 'reason' => $report->reason]], 201);
    }

    public function decide(string $report, Request $request, GetProfile $get, DecideReport $decide): JsonResponse
    {
        $data = $request->validate(['action' => ['required', Rule::in((array) config('social-network-moderation.actions'))], 'reason' => ['nullable', 'string', 'max:5000'], 'evidence' => ['sometimes', 'array', 'max:64']]);
        $decision = $decide->handle($get->forUser($request->user()->getAuthIdentifier()), ModerationReport::query()->findOrFail($report), $data['action'], $data['reason'] ?? null, $data['evidence'] ?? []);
        return response()->json(['data' => ['id' => $decision->getKey(), 'report_id' => $decision->report_id, 'action' => $decision->action]], 201);
    }
}
