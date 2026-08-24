<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Communities\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class CommunitiesLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-communities-livewire');
        Livewire::component('module-social-network-communities::creator', Components\Creator::class);
    }
}
