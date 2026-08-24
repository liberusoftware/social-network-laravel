<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\SocialGraph\Authorization\GateGraphAuthorizer;
use Liberu\SocialNetwork\SocialGraph\Contracts\GraphAuthorizer;

final class SocialGraphServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/social-network-social-graph.php', 'social-network-social-graph');
        $this->app->singleton(GraphAuthorizer::class, GateGraphAuthorizer::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Gate::define('social-network.social-graph.follow', function (?object $user, object $source): bool {
            return $user !== null && method_exists($source, 'getAttribute')
                && (string) $source->getAttribute('user_id') === (string) $user->getAuthIdentifier();
        });

        Gate::define('social-network.social-graph.friend', function (?object $user, object $source): bool {
            return $user !== null && method_exists($source, 'getAttribute')
                && (string) $source->getAttribute('user_id') === (string) $user->getAuthIdentifier();
        });

        Gate::define('social-network.social-graph.list', function (?object $user, object $owner): bool {
            return $user !== null && method_exists($owner, 'getAttribute')
                && (string) $owner->getAttribute('user_id') === (string) $user->getAuthIdentifier();
        });

        Gate::define('social-network.social-graph.block', function (?object $user, object $source): bool {
            return $user !== null
                && method_exists($source, 'getAttribute')
                && (string) $source->getAttribute('user_id') === (string) $user->getAuthIdentifier();
        });

        Gate::define('social-network.social-graph.visibility', function (?object $user, object $actor, object $relationship): bool {
            return $user !== null
                && method_exists($actor, 'getAttribute')
                && (string) $actor->getAttribute('user_id') === (string) $user->getAuthIdentifier()
                && method_exists($relationship, 'getAttribute')
                && (string) $relationship->getAttribute('source_profile_id') === (string) $actor->getKey();
        });
    }
}
