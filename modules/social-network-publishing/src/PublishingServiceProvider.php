<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\Publishing\Models\Publication;
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
        Gate::define('social-network.publishing.create', fn (object $user, Profile $author): bool => (string) $author->user_id === (string) $user->getAuthIdentifier());
        Gate::define('social-network.publishing.update', fn (object $user, Profile $author, Publication $publication): bool => (string) $author->user_id === (string) $user->getAuthIdentifier() && (string) $publication->author_profile_id === (string) $author->getKey());
        Gate::define('social-network.publishing.publish', fn (object $user, Profile $author, Publication $publication): bool => (string) $author->user_id === (string) $user->getAuthIdentifier() && (string) $publication->author_profile_id === (string) $author->getKey());
    }
}
