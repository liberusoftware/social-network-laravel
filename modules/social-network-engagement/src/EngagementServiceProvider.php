<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Engagement;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\Engagement\Authorization\GateEngagementAuthorizer;
use Liberu\SocialNetwork\Engagement\Contracts\EngagementAuthorizer;
use Liberu\SocialNetwork\Engagement\Models\Engagement;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class EngagementServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/social-network-engagement.php', 'social-network-engagement');
        $this->app->singleton(EngagementAuthorizer::class, GateEngagementAuthorizer::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        Gate::define('social-network.engagement.create', fn (object $user, Profile $actor): bool => (string) $actor->user_id === (string) $user->getAuthIdentifier());
        Gate::define('social-network.engagement.update', fn (object $user, Profile $actor, Engagement $engagement): bool => (string) $actor->user_id === (string) $user->getAuthIdentifier() && (string) $engagement->actor_profile_id === (string) $actor->getKey());
    }
}
