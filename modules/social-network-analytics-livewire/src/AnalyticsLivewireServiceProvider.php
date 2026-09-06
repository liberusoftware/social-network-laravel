<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Analytics\Livewire;

use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\Analytics\Livewire\Components\Dashboard;
use Livewire\Livewire;

final class AnalyticsLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-analytics-livewire');
        Livewire::component('module-social-network-analytics::dashboard', Dashboard::class);
    }
}
