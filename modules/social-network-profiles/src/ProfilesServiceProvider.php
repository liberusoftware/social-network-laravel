<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Profiles;

use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\Profiles\Authorization\GateProfileAuthorizer;
use Liberu\SocialNetwork\Profiles\Contracts\ProfileAuthorizer;
use Liberu\SocialNetwork\Profiles\Contracts\ProfileRepository;
use Liberu\SocialNetwork\Profiles\Repositories\EloquentProfileRepository;

final class ProfilesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/social-network-profiles.php', 'social-network-profiles');
        $this->app->singleton(ProfileRepository::class, EloquentProfileRepository::class);
        $this->app->singleton(ProfileAuthorizer::class, GateProfileAuthorizer::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
