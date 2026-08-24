<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Communities;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\Communities\Models\Community;
use Liberu\SocialNetwork\Communities\Authorization\GateCommunityAuthorizer;
use Liberu\SocialNetwork\Communities\Contracts\CommunityAuthorizer;

final class CommunitiesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/social-network-communities.php', 'social-network-communities');
        $this->app->singleton(CommunityAuthorizer::class, GateCommunityAuthorizer::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        Gate::define('social-network.communities.create', fn (object $user, Profile $owner): bool => (string) $owner->user_id === (string) $user->getAuthIdentifier());
        Gate::define('social-network.communities.join', fn (object $user, Profile $member): bool => (string) $member->user_id === (string) $user->getAuthIdentifier());
        Gate::define('social-network.communities.manage', fn (object $user, Profile $owner, Community $community): bool => (string) $owner->user_id === (string) $user->getAuthIdentifier() && (string) $community->owner_profile_id === (string) $owner->getKey());
    }
}
