<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Carbon;
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
        $starts = array_key_exists('starts_at', $data) ? Carbon::parse((string) $data['starts_at']) : $event->starts_at;
        $ends = array_key_exists('ends_at', $data) && $data['ends_at'] !== null ? Carbon::parse((string) $data['ends_at']) : ($data['ends_at'] ?? $event->ends_at);
        if ($ends !== null && $starts !== null && $ends <= $starts) {
            throw new InvalidArgumentException('The event end must be after its start.');
        }
        if (array_key_exists('title', $data) && trim((string) $data['title']) === '') {
            throw new InvalidArgumentException('The event title is required.');
        }
        if (array_key_exists('title', $data) && mb_strlen(trim((string) $data['title'])) > (int) config('social-network-events.max_title_length', 200)) {
            throw new InvalidArgumentException('The event title is too long.');
        }
        if (array_key_exists('description', $data) && $data['description'] !== null && mb_strlen((string) $data['description']) > (int) config('social-network-events.max_description_length', 20000)) {
            throw new InvalidArgumentException('The event description is too long.');
        }
        if (array_key_exists('capacity', $data) && $data['capacity'] !== null && (! is_numeric($data['capacity']) || (int) $data['capacity'] < 1)) {
            throw new InvalidArgumentException('The event capacity is invalid.');
        }
        if (($data['capacity'] ?? $event->capacity) !== null && (int) ($data['capacity'] ?? $event->capacity) < (int) DB::table('social_event_attendance')->where(['event_id' => $event->getKey(), 'state' => 'going'])->count()) {
            throw new InvalidArgumentException('The event capacity cannot be lower than current attendance.');
        }
        $updated = DB::transaction(function () use ($event, $data, $starts, $ends): Event {
            $attributes = ['starts_at' => $starts, 'ends_at' => $ends];
            foreach (['title', 'description', 'capacity', 'location', 'timezone', 'visibility'] as $field) {
                if (array_key_exists($field, $data)) {
                    $attributes[$field] = $field === 'title'
                        ? trim((string) $data[$field])
                        : ($field === 'capacity' && $data[$field] !== null ? (int) $data[$field] : $data[$field]);
                }
            }
            $event->update($attributes);

            return $event->refresh();
        });
        $this->events->dispatch(new EventUpdated($updated));

        return $updated;
    }
}
