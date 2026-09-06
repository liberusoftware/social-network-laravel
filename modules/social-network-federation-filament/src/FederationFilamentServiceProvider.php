<?php

namespace Liberu\SocialNetwork\Federation\Filament;

use Illuminate\Support\ServiceProvider;

class FederationFilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-federation-filament');
    }
}
