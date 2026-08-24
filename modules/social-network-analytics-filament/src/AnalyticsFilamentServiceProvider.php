<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Analytics\Filament;

use Illuminate\Support\ServiceProvider;

final class AnalyticsFilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-analytics-filament');
    }
}
