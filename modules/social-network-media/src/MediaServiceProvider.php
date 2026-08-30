<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\Media\Authorization\GateMediaAuthorizer;
use Liberu\SocialNetwork\Media\Contracts\MediaAuthorizer;
use Liberu\SocialNetwork\Media\Models\Album;
use Liberu\SocialNetwork\Media\Models\MediaAsset;
use Liberu\SocialNetwork\Profiles\Models\Profile;

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
        Gate::define('social-network.media.upload', fn (object $user, Profile $owner): bool => (string) $owner->user_id === (string) $user->getAuthIdentifier());
        Gate::define('social-network.media.update', fn (object $user, Profile $owner, MediaAsset $asset): bool => (string) $owner->user_id === (string) $user->getAuthIdentifier() && (string) $asset->owner_profile_id === (string) $owner->getKey());
        Gate::define('social-network.media.album.create', fn (object $user, Profile $owner): bool => (string) $owner->user_id === (string) $user->getAuthIdentifier());
        Gate::define('social-network.media.album.update', fn (object $user, Profile $owner, Album $album): bool => (string) $owner->user_id === (string) $user->getAuthIdentifier() && (string) $album->owner_profile_id === (string) $owner->getKey());
    }
}
