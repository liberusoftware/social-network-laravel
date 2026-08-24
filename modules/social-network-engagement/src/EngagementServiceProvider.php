<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Engagement;

use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\Engagement\Authorization\GateEngagementAuthorizer;
use Liberu\SocialNetwork\Engagement\Contracts\EngagementAuthorizer;

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
    }
}
