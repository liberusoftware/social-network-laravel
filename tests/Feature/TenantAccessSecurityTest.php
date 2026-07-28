<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantAccessSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_only_access_teams_they_belong_to(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $otherTeam = Team::factory()->create();
        $panel = Filament::getPanel('app');

        $this->assertTrue($user->canAccessTenant($user->currentTeam));
        $this->assertFalse($user->canAccessTenant($otherTeam));
        $this->assertTrue($user->canAccessPanel($panel));
    }

    public function test_regular_user_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->canAccessPanel(Filament::getPanel('admin')));
    }
}
