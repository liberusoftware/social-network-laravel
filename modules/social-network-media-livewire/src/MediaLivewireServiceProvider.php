<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class MediaLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-media-livewire');
        Livewire::component('module-social-network-media::register', Components\Register::class);
    }
}
