<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Analytics;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\Analytics\Authorization\GateAnalyticsAuthorizer;
use Liberu\SocialNetwork\Analytics\Contracts\AnalyticsAuthorizer;

final class AnalyticsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/social-network-analytics.php', 'social-network-analytics');
        $this->app->singleton(AnalyticsAuthorizer::class, GateAnalyticsAuthorizer::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/social-network-analytics.php' => config_path('social-network-analytics.php'),
        ], 'social-network-analytics-config');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        Gate::define('social-network.analytics.view', static fn (object $user, string $metric): bool => self::canManage($user, $metric));
        Gate::define('social-network.analytics.record', static fn (object $user, string $metric): bool => self::canManage($user, $metric));
    }

    private static function canManage(object $user, string $metric): bool
    {
        return $metric !== ''
            && (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()
                || method_exists($user, 'can') && $user->can('view analytics'));
    }
}
