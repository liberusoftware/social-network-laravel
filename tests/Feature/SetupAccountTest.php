<?php

use App\Filament\App\Pages\SetupAccount;
use App\Models\User;
use Illuminate\Support\Carbon;
use Liberu\Foundation\Organizations\Models\Team;
use Livewire\Livewire;

it('renders the setup wizard for a newly registered user', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'name' => "Ada's Team"]);
    $user->forceFill(['current_team_id' => $team->id])->save();

    $this->actingAs($user)
        ->get(route('filament.app.pages.setup-account'))
        ->assertOk()
        ->assertSee('A quick start for your workspace')
        ->assertSee('Finish setup');
});

it('sends an unfinished account to setup from the dashboard', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $user->forceFill(['current_team_id' => $team->id])->save();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('filament.app.pages.setup-account'));
});

it('saves profile and team details and completes onboarding', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'name' => "Ada's Team"]);
    $user->forceFill(['current_team_id' => $team->id])->save();
    $this->actingAs($user);

    Livewire::test(SetupAccount::class)
        ->set('data', [
            'name' => 'Ada Lovelace',
            'team_name' => 'Analytical Engines',
            'timezone' => 'Europe/London',
        ])
        ->call('completeSetup');

    expect($user->fresh()->name)->toBe('Ada Lovelace')
        ->and($user->fresh()->timezone)->toBe('Europe/London')
        ->and($user->fresh()->onboarding_completed_at)->toBeInstanceOf(Carbon::class)
        ->and($team->fresh()->name)->toBe('Analytical Engines');
});
