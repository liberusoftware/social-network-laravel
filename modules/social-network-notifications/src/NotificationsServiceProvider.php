<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications;

use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\Notifications\Authorization\GateNotificationAuthorizer;
use Liberu\SocialNetwork\Notifications\Contracts\NotificationAuthorizer;

final class NotificationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/social-network-notifications.php', 'social-network-notifications');
        $this->app->singleton(NotificationAuthorizer::class, GateNotificationAuthorizer::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
