<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Discovery\Filament;

use Illuminate\Support\ServiceProvider;

final class DiscoveryFilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-discovery-filament');
    }
}
