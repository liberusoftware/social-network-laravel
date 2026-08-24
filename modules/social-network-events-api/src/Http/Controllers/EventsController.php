<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\SocialNetwork\Events\Actions\CreateEvent;
use Liberu\SocialNetwork\Events\Actions\InviteProfile;
use Liberu\SocialNetwork\Events\Actions\ListEvents;
use Liberu\SocialNetwork\Events\Actions\PublishEvent;
use Liberu\SocialNetwork\Events\Actions\SetAttendance;
use Liberu\SocialNetwork\Events\Actions\UpdateEvent;
use Liberu\SocialNetwork\Events\Models\Event;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class EventsController extends Controller
{
    public function index(Request $request, GetProfile $get, ListEvents $list): JsonResponse
    {
        $data = $request->validate(['limit' => ['sometimes', 'integer', 'min:1', 'max:100']]);
        $events = $list->handle($get->forUser($request->user()->getAuthIdentifier()), $data['limit'] ?? 25);

        return response()->json(['data' => $events->map(fn (Event $event): array => $this->resource($event))->values()]);
    }

    public function store(Request $request, GetProfile $get, CreateEvent $create): JsonResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:20000'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'location' => ['sometimes', 'array'],
            'metadata' => ['sometimes', 'array'],
        ]);

        return response()->json([
            'data' => $this->resource($create->handle($get->forUser($request->user()->getAuthIdentifier()), $data)),
        ], 201);
    }

    public function update(Event $event, Request $request, GetProfile $get, UpdateEvent $update): JsonResponse
    {
        $data = $request->validate([
            'title' => ['sometimes', 'string', 'max:200'],
            'description' => ['sometimes', 'nullable', 'string', 'max:20000'],
            'starts_at' => ['sometimes', 'date'],
            'ends_at' => ['sometimes', 'nullable', 'date', 'after:starts_at'],
            'capacity' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:100000'],
            'location' => ['sometimes', 'array'],
            'timezone' => ['sometimes', 'timezone'],
            'visibility' => ['sometimes', 'in:public,private,unlisted'],
        ]);

        return response()->json([
            'data' => $this->resource($update->handle($get->forUser($request->user()->getAuthIdentifier()), $event, $data)),
        ]);
    }

    public function publish(Event $event, Request $request, GetProfile $get, PublishEvent $publish): JsonResponse
    {
        $updated = $publish->handle($get->forUser($request->user()->getAuthIdentifier()), $event);

        return response()->json(['data' => $this->resource($updated)]);
    }

    public function invite(Event $event, Request $request, GetProfile $get, InviteProfile $invite): JsonResponse
    {
        $data = $request->validate(['profile_id' => ['required', 'uuid']]);
        $invitation = $invite->handle(
            $get->forUser($request->user()->getAuthIdentifier()),
            $event,
            Profile::query()->findOrFail($data['profile_id']),
        );

        return response()->json(['data' => $invitation], 201);
    }

    public function attendance(Event $event, Request $request, GetProfile $get, SetAttendance $set): JsonResponse
    {
        $data = $request->validate(['state' => ['required', 'in:going,maybe,not_going']]);
        $set->handle($get->forUser($request->user()->getAuthIdentifier()), $event, $data['state']);

        return response()->json(['data' => ['event_id' => $event->getKey(), 'state' => $data['state']]]);
    }

    private function resource(Event $event): array
    {
        return [
            'id' => $event->getKey(),
            'type' => 'social-network-events',
            'state' => $event->state,
            'title' => $event->title,
            'description' => $event->description,
            'starts_at' => $event->starts_at?->toISOString(),
            'ends_at' => $event->ends_at?->toISOString(),
            'capacity' => $event->capacity,
            'location' => $event->location,
            'visibility' => $event->visibility,
            'timezone' => $event->timezone,
        ];
    }
}
