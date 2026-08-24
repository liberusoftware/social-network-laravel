<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging;

use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\Messaging\Authorization\GateMessagingAuthorizer;
use Liberu\SocialNetwork\Messaging\Contracts\MessagingAuthorizer;

final class MessagingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/social-network-messaging.php', 'social-network-messaging');
        $this->app->singleton(MessagingAuthorizer::class, GateMessagingAuthorizer::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
