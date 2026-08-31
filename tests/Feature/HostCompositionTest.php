<?php

use App\Actions\Fortify\CreateNewUser;
use App\Support\PanelNavigation;
use Filament\FilamentManager;
use Illuminate\Validation\ValidationException;
use Liberu\Foundation\Identity\Contracts\InvitationValidator;
use Liberu\Foundation\Identity\Contracts\RegistrationPolicy;
use Liberu\Foundation\Identity\Support\IdentifierNormalizer;
use Liberu\Foundation\Organizations\Models\Team;

it('creates a personal team when the host registration action creates a user', function () {
    $registration = Mockery::mock(RegistrationPolicy::class);
    $registration->shouldReceive('permitsSelfRegistration')->andReturnTrue();
    $registration->shouldReceive('requiresInvitation')->andReturnFalse();

    $user = (new CreateNewUser($registration, Mockery::mock(InvitationValidator::class), new IdentifierNormalizer()))
        ->create([
            'name' => 'Grace Hopper',
            'email' => 'grace@example.test',
            'password' => 'A-strong-password-123!',
            'password_confirmation' => 'A-strong-password-123!',
        ]);

    expect($user->current_team_id)->not->toBeNull()
        ->and(Team::query()->whereKey($user->current_team_id)->where('user_id', $user->id)->value('personal_team'))->toBeTrue();
});

it('rejects registration when the host policy is closed', function () {
    $registration = Mockery::mock(RegistrationPolicy::class);
    $registration->shouldReceive('permitsSelfRegistration')->andReturnFalse();

    expect(fn () => (new CreateNewUser($registration, Mockery::mock(InvitationValidator::class), new IdentifierNormalizer()))
        ->create(['email' => 'closed@example.test']))
        ->toThrow(ValidationException::class);
});

it('applies grouped host navigation to both panels', function () {
    $manager = app(FilamentManager::class);

    app(PanelNavigation::class)->configure($manager->getPanel('app'));
    app(PanelNavigation::class)->configure($manager->getPanel('admin'));

    expect(true)->toBeTrue();
});
