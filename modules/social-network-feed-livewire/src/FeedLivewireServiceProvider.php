<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Feed\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class FeedLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-feed-livewire');
        Livewire::component('module-social-network-feed::timeline', Components\Timeline::class);
    }
}
