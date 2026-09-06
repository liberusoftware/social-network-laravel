<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\SocialNetwork\Events\Contracts\EventsAuthorizer;
use Liberu\SocialNetwork\Events\Events\EventUpdateCreated;
use Liberu\SocialNetwork\Events\Models\Event;
use Liberu\SocialNetwork\Events\Models\EventUpdate;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class AddEventUpdate
{
    public function __construct(
        private EventsAuthorizer $authorizer,
        private Dispatcher $events,
    ) {}

    public function handle(Profile $owner, Event $event, string $body): EventUpdate
    {
        $this->authorizer->manage($owner, $event);
        $body = trim($body);

        if ($body === '' || mb_strlen($body) > 20000) {
            throw new InvalidArgumentException('The event update is invalid.');
        }

        $update = DB::transaction(fn (): EventUpdate => EventUpdate::query()->create([
            'event_id' => $event->getKey(),
            'author_profile_id' => $owner->getKey(),
            'body' => $body,
        ]));
        $this->events->dispatch(new EventUpdateCreated($update));

        return $update;
    }
}
