<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Moderation;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\Moderation\Authorization\GateModerationAuthorizer;
use Liberu\SocialNetwork\Moderation\Contracts\ModerationAuthorizer;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class ModerationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/social-network-moderation.php', 'social-network-moderation');
        $this->app->singleton(ModerationAuthorizer::class, GateModerationAuthorizer::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Gate::define('social-network.moderation.report', function (object $user, Profile $reporter): bool {
            return (string) $reporter->user_id === (string) $user->getAuthIdentifier();
        });

        Gate::define('social-network.moderation.decide', fn (object $user, Profile $actor): bool => (method_exists($user, 'isAdmin') && $user->isAdmin()) || (bool) ($user->is_admin ?? false));
    }
}
