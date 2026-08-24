<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Engagement\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class EngagementLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-engagement-livewire');
        Livewire::component('module-social-network-engagement::reactor', Components\Reactor::class);
    }
}
