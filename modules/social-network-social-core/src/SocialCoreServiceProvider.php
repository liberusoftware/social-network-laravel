<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialCore;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\SocialCore\Authorization\GateSocialNetworkSettingsAuthorizer;
use Liberu\SocialNetwork\SocialCore\Contracts\SocialNetworkSettingsAuthorizer;
use Liberu\SocialNetwork\SocialCore\Contracts\SocialNetworkSettingsRepository;
use Liberu\SocialNetwork\SocialCore\Repositories\EloquentSocialNetworkSettingsRepository;

final class SocialCoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/social-network-social-core.php', 'social-network-social-core');
        $this->app->singleton(SocialNetworkSettingsRepository::class, EloquentSocialNetworkSettingsRepository::class);
        $this->app->singleton(SocialNetworkSettingsAuthorizer::class, GateSocialNetworkSettingsAuthorizer::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Gate::define('social-network.social-core.view', function (?object $user, int|string $teamId): bool {
            return $this->isTeamMember($user, $teamId);
        });

        Gate::define('social-network.social-core.update', function (?object $user, int|string $teamId): bool {
            return $this->isTeamMember($user, $teamId);
        });
    }

    private function isTeamMember(?object $user, int|string $teamId): bool
    {
        if ($user === null) {
            return false;
        }

        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }

        $teamModel = config('social-network-social-core.team_model');
        if (! is_string($teamModel) || ! is_a($teamModel, Model::class, true)) {
            return false;
        }

        $team = $teamModel::query()->find($teamId);

        return $team !== null && method_exists($user, 'belongsToTeam') && $user->belongsToTeam($team);
    }
}
