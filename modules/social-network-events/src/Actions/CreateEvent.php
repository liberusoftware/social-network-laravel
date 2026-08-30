<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Liberu\SocialNetwork\Events\Contracts\EventsAuthorizer;
use Liberu\SocialNetwork\Events\Events\EventCreated;
use Liberu\SocialNetwork\Events\Models\Event;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class CreateEvent
{
    public function __construct(
        private EventsAuthorizer $authorizer,
        private Dispatcher $events,
    ) {}

    /** @param array<string, mixed> $data */
    public function handle(Profile $owner, array $data): Event
    {
        $this->authorizer->create($owner);
        $title = trim((string) ($data['title'] ?? ''));
        $description = $data['description'] ?? null;
        if (! isset($data['starts_at'])) {
            throw new InvalidArgumentException('An event start time is required.');
        }
        $starts = Carbon::parse((string) $data['starts_at']);
        $ends = isset($data['ends_at']) ? Carbon::parse((string) $data['ends_at']) : null;
        $capacity = $data['capacity'] ?? null;

        if ($title === '' || mb_strlen($title) > (int) config('social-network-events.max_title_length', 200)
            || ($description !== null && mb_strlen((string) $description) > (int) config('social-network-events.max_description_length', 20000))
            || ($ends !== null && $ends->lessThanOrEqualTo($starts))
            || ($capacity !== null && (! is_numeric($capacity) || (int) $capacity < 1))) {
            throw new InvalidArgumentException('The event details are invalid.');
        }

        $event = DB::transaction(fn (): Event => Event::query()->create([
            'id' => (string) Str::uuid(),
            'owner_profile_id' => $owner->getKey(),
            'title' => $title,
            'description' => $description,
            'starts_at' => $starts,
            'ends_at' => $ends,
            'capacity' => $capacity === null ? null : (int) $capacity,
            'location' => $data['location'] ?? [],
            'metadata' => $data['metadata'] ?? [],
            'state' => 'draft',
        ]));

        $this->events->dispatch(new EventCreated($event));

        return $event;
    }
}
