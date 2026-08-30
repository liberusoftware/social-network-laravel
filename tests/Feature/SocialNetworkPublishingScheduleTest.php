<?php

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\Publishing\Actions\PublishScheduledPublications;
use Liberu\SocialNetwork\Publishing\Models\Publication;

it('publishes due publications and leaves future schedules untouched', function (): void {
    $user = User::factory()->create();
    $profile = Profile::query()->create([
        'id' => (string) Str::uuid(),
        'user_id' => $user->getKey(),
        'handle' => 'schedule-'.strtolower(substr((string) $user->getKey(), 0, 8)),
    ]);
    $due = Publication::query()->create([
        'id' => (string) Str::uuid(),
        'author_profile_id' => $profile->getKey(),
        'kind' => 'post',
        'state' => 'draft',
        'audience' => 'public',
        'body' => 'Due now',
        'scheduled_at' => Carbon::parse('2026-08-25 00:00:00'),
    ]);
    $future = Publication::query()->create([
        'id' => (string) Str::uuid(),
        'author_profile_id' => $profile->getKey(),
        'kind' => 'post',
        'state' => 'draft',
        'audience' => 'public',
        'body' => 'Later',
        'scheduled_at' => Carbon::parse('2026-08-26 00:00:00'),
    ]);

    expect(app(PublishScheduledPublications::class)->handle(Carbon::parse('2026-08-25 12:00:00')))->toBe(1)
        ->and($due->refresh()->state)->toBe('published')
        ->and($due->refresh()->published_at?->toDateString())->toBe('2026-08-25')
        ->and($future->refresh()->state)->toBe('draft');
});
