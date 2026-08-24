<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialCore;

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
    }
}
