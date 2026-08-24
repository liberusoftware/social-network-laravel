<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Moderation\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\SocialNetwork\Moderation\Actions\CreateReport;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;

final class ModerationController extends Controller
{
    public function report(Request $request, GetProfile $get, CreateReport $create): JsonResponse
    {
        $data = $request->validate(['target_type' => ['required', 'string', 'max:160'], 'target_id' => ['required', 'uuid'], 'reason' => ['required', 'string', 'max:120'], 'details' => ['nullable', 'string', 'max:10000']]);
        $report = $create->handle($get->forUser($request->user()->getAuthIdentifier()), $data['target_type'], $data['target_id'], $data['reason'], $data['details'] ?? null);

        return response()->json(['data' => ['id' => $report->getKey(), 'type' => 'social-network-moderation-reports', 'state' => $report->state, 'reason' => $report->reason]], 201);
    }
}
