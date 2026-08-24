<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Profiles\Models\Profile;
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
        Gate::define('social-network.messaging.create', fn (object $user, Profile $actor): bool => (string) $actor->user_id === (string) $user->getAuthIdentifier());
        Gate::define('social-network.messaging.send', fn (object $user, Profile $actor): bool => (string) $actor->user_id === (string) $user->getAuthIdentifier());
    }
}
