<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Liberu\SocialNetwork\Events\Contracts\EventsAuthorizer;
use Liberu\SocialNetwork\Events\Events\EventPublished;
use Liberu\SocialNetwork\Events\Models\Event;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class PublishEvent
{
    public function __construct(
        private EventsAuthorizer $authorizer,
        private Dispatcher $events,
    ) {}

    public function handle(Profile $owner, Event $event): Event
    {
        $this->authorizer->manage($owner, $event);
        $event->update(['state' => 'published']);
        $event = $event->refresh();
        $this->events->dispatch(new EventPublished($event));

        return $event;
    }
}
