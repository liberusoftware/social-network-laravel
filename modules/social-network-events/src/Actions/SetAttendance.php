<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\SocialNetwork\Events\Contracts\EventsAuthorizer;
use Liberu\SocialNetwork\Events\Models\Event;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class SetAttendance
{
    public function __construct(private EventsAuthorizer $authorizer) {}

    public function handle(Profile $profile, Event $event, string $state): void
    {
        $this->authorizer->attend($profile, $event);

        if (! in_array($state, (array) config('social-network-events.attendance_states'), true)) {
            throw new InvalidArgumentException('Attendance state is unsupported.');
        }

        DB::transaction(function () use ($profile, $event, $state): void {
            $lockedEvent = Event::query()->whereKey($event->getKey())->lockForUpdate()->firstOrFail();
            $current = DB::table('social_event_attendance')->where([
                'event_id' => $lockedEvent->getKey(),
                'profile_id' => $profile->getKey(),
            ])->first();

            if ($state === 'going' && $lockedEvent->capacity !== null && ($current?->state !== 'going') && DB::table('social_event_attendance')
                ->where(['event_id' => $lockedEvent->getKey(), 'state' => 'going'])
                ->count() >= $lockedEvent->capacity) {
                throw new InvalidArgumentException('Event capacity has been reached.');
            }

            if ($current === null) {
                DB::table('social_event_attendance')->insert([
                    'event_id' => $lockedEvent->getKey(),
                    'profile_id' => $profile->getKey(),
                    'state' => $state,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('social_event_attendance')->where('id', $current->id)->update([
                    'state' => $state,
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
