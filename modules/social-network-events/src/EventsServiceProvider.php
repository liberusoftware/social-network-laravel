<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\Events\Authorization\GateEventsAuthorizer;
use Liberu\SocialNetwork\Events\Contracts\EventsAuthorizer;
use Liberu\SocialNetwork\Events\Models\Event;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class EventsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/social-network-events.php', 'social-network-events');
        $this->app->singleton(EventsAuthorizer::class, GateEventsAuthorizer::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        Gate::define('social-network.events.create', fn (object $user, Profile $owner): bool => (string) $owner->user_id === (string) $user->getAuthIdentifier());
        Gate::define('social-network.events.manage', fn (object $user, Profile $owner, Event $event): bool => (string) $owner->user_id === (string) $user->getAuthIdentifier() && (string) $event->owner_profile_id === (string) $owner->getKey());
        Gate::define('social-network.events.attend', fn (object $user, Profile $profile, Event $event): bool => (string) $profile->user_id === (string) $user->getAuthIdentifier() && $event->state === 'published');
    }
}
