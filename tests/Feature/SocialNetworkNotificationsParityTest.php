<?php

use App\Models\User;
use Illuminate\Support\Str;
use Liberu\SocialNetwork\Notifications\Models\SocialNotification;
use Liberu\SocialNetwork\Profiles\Models\Profile;

it('reports unread notifications and marks all of the owner notifications read', function (): void {
    config()->set('social-network-profiles.user_model', User::class);
    $user = User::factory()->create();
    $profile = Profile::query()->create(['id' => (string) Str::uuid(), 'user_id' => $user->getKey(), 'handle' => 'notifications-'.strtolower(substr((string) $user->getKey(), 0, 8))]);
    SocialNotification::query()->create(['id' => (string) Str::uuid(), 'profile_id' => $profile->getKey(), 'kind' => 'follow', 'channel' => 'in_app', 'state' => 'unread', 'payload' => ['actor' => 'one']]);
    SocialNotification::query()->create(['id' => (string) Str::uuid(), 'profile_id' => $profile->getKey(), 'kind' => 'comment', 'channel' => 'in_app', 'state' => 'unread', 'payload' => ['actor' => 'two']]);

    $this->actingAs($user)->getJson('/api/v1/social-network/notifications/unread-count')
        ->assertSuccessful()
        ->assertJsonPath('count', 2);

    $this->actingAs($user)->postJson('/api/v1/social-network/notifications/read-all')
        ->assertSuccessful()
        ->assertJsonPath('marked', 2);

    expect(SocialNotification::query()->where('profile_id', $profile->getKey())->where('state', 'unread')->count())->toBe(0);
});
