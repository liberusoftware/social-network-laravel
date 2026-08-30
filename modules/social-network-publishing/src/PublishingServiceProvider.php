<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\Publishing\Actions\PublishScheduledPublications;
use Liberu\SocialNetwork\Publishing\Authorization\GatePublishingAuthorizer;
use Liberu\SocialNetwork\Publishing\Contracts\PublishingAuthorizer;
use Liberu\SocialNetwork\Publishing\Models\Publication;

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
        $this->app->booted(function (): void {
            $this->app->make(Schedule::class)
                ->call(fn (): int => $this->app->make(PublishScheduledPublications::class)->handle())
                ->name('social-network-publishing.publish-scheduled')
                ->everyMinute()
                ->withoutOverlapping();
        });
        Gate::define('social-network.publishing.create', fn (object $user, Profile $author): bool => (string) $author->user_id === (string) $user->getAuthIdentifier());
        Gate::define('social-network.publishing.update', fn (object $user, Profile $author, Publication $publication): bool => (string) $author->user_id === (string) $user->getAuthIdentifier() && (string) $publication->author_profile_id === (string) $author->getKey());
        Gate::define('social-network.publishing.publish', fn (object $user, Profile $author, Publication $publication): bool => (string) $author->user_id === (string) $user->getAuthIdentifier() && (string) $publication->author_profile_id === (string) $author->getKey());
    }
}
