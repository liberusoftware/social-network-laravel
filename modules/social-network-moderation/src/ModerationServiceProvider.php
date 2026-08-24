<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Moderation;

use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\Moderation\Authorization\GateModerationAuthorizer;
use Liberu\SocialNetwork\Moderation\Contracts\ModerationAuthorizer;

final class ModerationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/social-network-moderation.php', 'social-network-moderation');
        $this->app->singleton(ModerationAuthorizer::class, GateModerationAuthorizer::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
