<?php

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Liberu\SocialNetwork\Events\Actions\AddEventUpdate;
use Liberu\SocialNetwork\Events\Actions\ListEvents;
use Liberu\SocialNetwork\Events\Actions\SetAttendance;
use Liberu\SocialNetwork\Events\Models\Event;
use Liberu\SocialNetwork\Events\Models\EventUpdate;
use Liberu\SocialNetwork\Profiles\Models\Profile;

function eventProfile(): Profile
{
    config()->set('social-network-profiles.user_model', User::class);
    $user = User::factory()->create();

    return Profile::query()->create([
        'id' => (string) Str::uuid(),
        'user_id' => $user->getKey(),
        'handle' => 'event-'.strtolower(substr((string) $user->getKey(), 0, 8)),
    ]);
}

function publishedEvent(Profile $owner, ?int $capacity = null, string $visibility = 'public'): Event
{
    return Event::query()->create([
        'id' => (string) Str::uuid(),
        'owner_profile_id' => $owner->getKey(),
        'state' => 'published',
        'visibility' => $visibility,
        'title' => 'Community event',
        'starts_at' => Carbon::now()->addDay(),
        'capacity' => $capacity,
    ]);
}

it('does not leak private events and exposes them to invited profiles', function (): void {
    $owner = eventProfile();
    $viewer = eventProfile();
    $event = publishedEvent($owner, visibility: 'private');

    expect(app(ListEvents::class)->handle($viewer))->toHaveCount(0);

    DB::table('social_event_invitations')->insert([
        'event_id' => $event->getKey(),
        'profile_id' => $viewer->getKey(),
        'state' => 'pending',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    expect(app(ListEvents::class)->handle($viewer))->toHaveCount(1);
});

it('enforces event capacity transactionally and permits event updates', function (): void {
    $owner = eventProfile();
    $first = eventProfile();
    $second = eventProfile();
    $event = publishedEvent($owner, capacity: 1);

    $this->actingAs($first->user);
    app(SetAttendance::class)->handle($first, $event, 'going');

    $this->actingAs($second->user);
    expect(fn (): mixed => app(SetAttendance::class)->handle($second, $event, 'going'))
        ->toThrow(InvalidArgumentException::class, 'capacity');

    $this->actingAs($owner->user);
    $update = app(AddEventUpdate::class)->handle($owner, $event, 'The location has changed.');

    expect($update)->toBeInstanceOf(EventUpdate::class)
        ->and($update->body)->toBe('The location has changed.');
});
