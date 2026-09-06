<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Discovery;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\Discovery\Authorization\GateDiscoveryAuthorizer;
use Liberu\SocialNetwork\Discovery\Contracts\DiscoveryAuthorizer;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class DiscoveryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/social-network-discovery.php', 'social-network-discovery');
        $this->app->singleton(DiscoveryAuthorizer::class, GateDiscoveryAuthorizer::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        Gate::define('social-network.discovery.search', fn (object $user, Profile $viewer): bool => (string) $viewer->user_id === (string) $user->getAuthIdentifier());
        Gate::define('social-network.discovery.index', fn (object $user, Profile $owner): bool => (string) $owner->user_id === (string) $user->getAuthIdentifier());
    }
}
