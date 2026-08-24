<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media;

use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\Media\Authorization\GateMediaAuthorizer;
use Liberu\SocialNetwork\Media\Contracts\MediaAuthorizer;

final class MediaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/social-network-media.php', 'social-network-media');
        $this->app->singleton(MediaAuthorizer::class, GateMediaAuthorizer::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
