<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialCore\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class SocialCoreLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-social-core-livewire');

        foreach ([
            'settings',
            'network-settings',
            'deployment-mode',
            'terminology',
            'feature-policy',
            'shared-ids',
            'events',
        ] as $component) {
            Livewire::component("module-social-network-social-core::{$component}", Components\SocialCoreSettings::class);
        }
    }
}
