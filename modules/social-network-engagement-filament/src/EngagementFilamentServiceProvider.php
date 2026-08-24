<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Engagement\Filament;

use Illuminate\Support\ServiceProvider;

final class EngagementFilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-engagement-filament');
    }
}
