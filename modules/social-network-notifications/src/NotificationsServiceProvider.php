<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\Notifications\Authorization\GateNotificationAuthorizer;
use Liberu\SocialNetwork\Notifications\Contracts\NotificationAuthorizer;
use Liberu\SocialNetwork\Profiles\Models\Profile;

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
        Gate::define('social-network.notifications.view', static fn (object $user, Profile $profile): bool => (string) $profile->user_id === (string) $user->getAuthIdentifier());
        Gate::define('social-network.notifications.manage', static fn (object $user, Profile $profile): bool => (string) $profile->user_id === (string) $user->getAuthIdentifier());
        Broadcast::channel('social-notifications.{profile}', static fn (object $user, string $profile): bool => Profile::query()->whereKey($profile)->where('user_id', $user->getAuthIdentifier())->exists());
    }
}
