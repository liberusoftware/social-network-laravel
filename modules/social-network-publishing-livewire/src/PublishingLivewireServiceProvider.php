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
        Livewire::component('module-social-network-publishing::posts', Components\Composer::class);
        Livewire::component('module-social-network-publishing::articles', Components\Composer::class);
        Livewire::component('module-social-network-publishing::drafts', Components\Composer::class);
        Livewire::component('module-social-network-publishing::audiences', Components\Composer::class);
        Livewire::component('module-social-network-publishing::edits', Components\Composer::class);
        Livewire::component('module-social-network-publishing::mentions', Components\Composer::class);
        Livewire::component('module-social-network-publishing::hashtags', Components\Composer::class);
        Livewire::component('module-social-network-publishing::polls', Components\Composer::class);
        Livewire::component('module-social-network-publishing::links', Components\Composer::class);
        Livewire::component('module-social-network-publishing::schedules', Components\Composer::class);
    }
}
