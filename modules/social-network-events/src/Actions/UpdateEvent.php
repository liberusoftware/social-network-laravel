<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\SocialNetwork\Events\Contracts\EventsAuthorizer;
use Liberu\SocialNetwork\Events\Events\EventUpdated;
use Liberu\SocialNetwork\Events\Models\Event;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class UpdateEvent
{
    public function __construct(
        private EventsAuthorizer $authorizer,
        private Dispatcher $events,
    ) {}

    /** @param array<string, mixed> $data */
    public function handle(Profile $owner, Event $event, array $data): Event
    {
        $this->authorizer->manage($owner, $event);
        $starts = $data['starts_at'] ?? $event->starts_at;
        $ends = $data['ends_at'] ?? $event->ends_at;
        if ($ends !== null && $starts !== null && $ends < $starts) {
            throw new InvalidArgumentException('The event end must be after its start.');
        }
        $updated = DB::transaction(function () use ($event, $data, $starts, $ends): Event {
            $event->update(array_filter([
                'title' => array_key_exists('title', $data) ? trim((string) $data['title']) : null,
                'description' => $data['description'] ?? null,
                'starts_at' => $starts,
                'ends_at' => $ends,
                'capacity' => $data['capacity'] ?? null,
                'location' => $data['location'] ?? null,
                'timezone' => $data['timezone'] ?? null,
                'visibility' => $data['visibility'] ?? null,
            ], static fn (mixed $value): bool => $value !== null));
            return $event->refresh();
        });
        $this->events->dispatch(new EventUpdated($updated));
        return $updated;
    }
}
