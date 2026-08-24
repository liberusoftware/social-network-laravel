<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class PublishingLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-publishing-livewire');
        Livewire::component('module-social-network-publishing::composer', Components\Composer::class);
    }
}
