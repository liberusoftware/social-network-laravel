<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class EventsLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-events-livewire');
        Livewire::component('module-social-network-events::creator', Components\Creator::class);
    }
}
