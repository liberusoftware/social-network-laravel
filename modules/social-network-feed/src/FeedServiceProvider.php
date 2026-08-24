<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Feed;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\Feed\Authorization\GateFeedAuthorizer;
use Liberu\SocialNetwork\Feed\Contracts\FeedAuthorizer;

final class FeedServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/social-network-feed.php', 'social-network-feed');
        $this->app->singleton(FeedAuthorizer::class, GateFeedAuthorizer::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        Gate::define('social-network.feed.view', fn (object $user, Profile $viewer): bool => (string) $viewer->user_id === (string) $user->getAuthIdentifier());
    }
}
