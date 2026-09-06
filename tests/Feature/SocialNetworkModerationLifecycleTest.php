<?php

use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Liberu\SocialNetwork\Moderation\Actions\CreateReport;
use Liberu\SocialNetwork\Moderation\Actions\DecideReport;
use Liberu\SocialNetwork\Moderation\Events\ReportDecided;
use Liberu\SocialNetwork\Profiles\Models\Profile;

function moderationProfile(?string $email = null): Profile
{
    config()->set('social-network-profiles.user_model', User::class);
    $user = User::factory()->create($email === null ? [] : ['email' => $email]);

    return Profile::query()->create([
        'id' => (string) Str::uuid(),
        'user_id' => $user->getKey(),
        'handle' => 'moderation-'.strtolower(substr((string) $user->getKey(), 0, 8)),
    ]);
}

it('prevents duplicate open reports and records an auditable decision event', function (): void {
    Event::fake();
    $reporter = moderationProfile();
    $admin = moderationProfile('moderator@example.test');
    config()->set('app.admin_emails', ['moderator@example.test']);
    $target = (string) Str::uuid();

    $this->actingAs($reporter->user);
    $report = app(CreateReport::class)->handle($reporter, 'publication', $target, 'spam');

    expect(fn (): mixed => app(CreateReport::class)->handle($reporter, 'publication', $target, 'spam'))
        ->toThrow(InvalidArgumentException::class, 'already exists');

    $this->actingAs($admin->user);
    $decision = app(DecideReport::class)->handle($admin, $report, 'warn', 'Warning issued', ['source' => 'review']);

    expect($report->refresh()->state)->toBe('resolved')
        ->and($decision->action)->toBe('warn');
    Event::assertDispatched(ReportDecided::class);

    expect(fn (): mixed => app(DecideReport::class)->handle($admin, $report->refresh(), 'dismiss'))
        ->toThrow(InvalidArgumentException::class, 'no longer actionable');
});
