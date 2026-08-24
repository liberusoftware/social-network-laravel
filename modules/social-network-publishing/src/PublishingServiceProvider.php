<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing;

use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\Publishing\Authorization\GatePublishingAuthorizer;
use Liberu\SocialNetwork\Publishing\Contracts\PublishingAuthorizer;

final class PublishingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/social-network-publishing.php', 'social-network-publishing');
        $this->app->singleton(PublishingAuthorizer::class, GatePublishingAuthorizer::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
