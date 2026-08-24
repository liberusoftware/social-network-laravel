<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Filament;

use Illuminate\Support\ServiceProvider;

final class SocialGraphFilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-social-graph-filament');
    }
}
