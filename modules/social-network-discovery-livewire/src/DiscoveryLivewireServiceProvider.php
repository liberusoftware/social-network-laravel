<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Discovery\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class DiscoveryLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-discovery-livewire');
        Livewire::component('module-social-network-discovery::search', Components\Search::class);
        Livewire::component('module-social-network-discovery::trends', Components\Search::class);
        Livewire::component('module-social-network-discovery::recommendations', Components\Search::class);
        Livewire::component('module-social-network-discovery::directories', Components\Search::class);
    }
}
