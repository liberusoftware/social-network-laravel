<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Communities;

use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\Communities\Authorization\GateCommunityAuthorizer;
use Liberu\SocialNetwork\Communities\Contracts\CommunityAuthorizer;

final class CommunitiesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/social-network-communities.php', 'social-network-communities');
        $this->app->singleton(CommunityAuthorizer::class, GateCommunityAuthorizer::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
