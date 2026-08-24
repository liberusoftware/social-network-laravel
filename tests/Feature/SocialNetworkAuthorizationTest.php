<?php

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Liberu\Foundation\Organizations\Models\Team;
use Liberu\SocialNetwork\Profiles\Models\Profile;

it('denies unauthenticated social abilities and leaves other abilities untouched', function () {
    expect(Gate::forUser(null)->allows('social-network.profiles.view', [new Profile(['visibility' => 'public'])]))->toBeFalse()
        ->and(Gate::forUser(null)->allows('viewTelescope'))->toBeFalse();
});

it('allows profile owners and public profile reads while denying private reads', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $owned = new Profile(['user_id' => $owner->getKey(), 'visibility' => 'private']);
    $public = new Profile(['user_id' => $other->getKey(), 'visibility' => 'public']);
    $private = new Profile(['user_id' => $other->getKey(), 'visibility' => 'private']);

    expect(Gate::forUser($owner)->allows('social-network.profiles.update', [$owned]))->toBeTrue()
        ->and(Gate::forUser($owner)->allows('social-network.profiles.view', [$public]))->toBeTrue()
        ->and(Gate::forUser($owner)->allows('social-network.profiles.view', [$private]))->toBeFalse();
});

it('allows team members to access team-scoped social settings', function () {
    $owner = User::factory()->create();
    $stranger = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $owner->getKey()]);

    expect(Gate::forUser($owner)->allows('social-network.social-core.view', [$team->getKey()]))->toBeTrue()
        ->and(Gate::forUser($stranger)->allows('social-network.social-core.view', [$team->getKey()]))->toBeFalse();
});

it('allows an allowlisted administrator to bypass social ability subjects', function () {
    $admin = User::factory()->create(['email' => 'social-admin@example.test']);
    config()->set('app.admin_emails', ['social-admin@example.test']);

    expect(Gate::forUser($admin)->allows('social-network.messaging.send', [new stdClass()]))->toBeTrue();
});

it('denies social abilities with unsupported subjects by default', function () {
    $user = User::factory()->create();

    expect(Gate::forUser($user)->allows('social-network.messaging.send', [new stdClass()]))->toBeFalse();
});
