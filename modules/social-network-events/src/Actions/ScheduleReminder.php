<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\SocialNetwork\Events\Contracts\EventsAuthorizer;
use Liberu\SocialNetwork\Events\Models\Event;
use Liberu\SocialNetwork\Events\Models\EventReminder;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class ScheduleReminder
{
    public function __construct(private EventsAuthorizer $authorizer) {}

    public function handle(Profile $profile, Event $event, string $sendAt): EventReminder
    {
        $this->authorizer->attend($profile, $event);
        $sendAt = Carbon::parse($sendAt);

        if ($sendAt->greaterThanOrEqualTo($event->starts_at)) {
            throw new InvalidArgumentException('A reminder must be scheduled before the event starts.');
        }

        return DB::transaction(fn (): EventReminder => EventReminder::query()->updateOrCreate(
            ['event_id' => $event->getKey(), 'profile_id' => $profile->getKey(), 'send_at' => $sendAt],
            ['sent_at' => null],
        ));
    }
}
