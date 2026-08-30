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
        Livewire::component('module-social-network-publishing::posts', Components\Posts::class);
        Livewire::component('module-social-network-publishing::articles', Components\Articles::class);
        Livewire::component('module-social-network-publishing::drafts', Components\Drafts::class);
        Livewire::component('module-social-network-publishing::audiences', Components\Audiences::class);
        Livewire::component('module-social-network-publishing::edits', Components\Edits::class);
        Livewire::component('module-social-network-publishing::mentions', Components\Mentions::class);
        Livewire::component('module-social-network-publishing::hashtags', Components\Hashtags::class);
        Livewire::component('module-social-network-publishing::polls', Components\Polls::class);
        Livewire::component('module-social-network-publishing::links', Components\Links::class);
        Livewire::component('module-social-network-publishing::schedules', Components\Schedules::class);
    }
}
