<?php

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\SocialNetwork\Moderation\Actions\CreateReport;
use Liberu\SocialNetwork\Moderation\Models\ModerationReport;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;

it('creates a report for the authenticated user profile', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $reporter = app(GetProfile::class)->forUser($user->getKey());
    $targetId = (string) str()->uuid();

    $report = app(CreateReport::class)->handle(
        $reporter,
        'post',
        $targetId,
        'spam',
        'Repeated unsolicited promotion.',
    );

    expect($report)->toBeInstanceOf(ModerationReport::class)
        ->and($report->state)->toBe('open')
        ->and($report->reporter_profile_id)->toBe($reporter->getKey())
        ->and(DB::table('social_moderation_reports')->where('id', $report->getKey())->exists())->toBeTrue();
});

it('rejects a report made for another user profile', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $this->actingAs($user);
    $otherProfile = app(GetProfile::class)->forUser($otherUser->getKey());

    expect(fn () => app(CreateReport::class)->handle(
        $otherProfile,
        'post',
        (string) str()->uuid(),
        'spam',
    ))->toThrow(AuthorizationException::class);
});

it('rejects malformed report targets before persistence', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $reporter = app(GetProfile::class)->forUser($user->getKey());

    expect(fn () => app(CreateReport::class)->handle($reporter, '', 'not-a-uuid', ''))
        ->toThrow(InvalidArgumentException::class, 'target and reason are required');
});
