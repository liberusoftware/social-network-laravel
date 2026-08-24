<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class SocialGraphLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-social-graph-livewire');
        Livewire::component('module-social-network-social-graph::follow-profile', Components\FollowProfile::class);
    }
}
