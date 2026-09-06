<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialCore\Livewire;

use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\SocialCore\Livewire\Components\DeploymentMode;
use Liberu\SocialNetwork\SocialCore\Livewire\Components\Events;
use Liberu\SocialNetwork\SocialCore\Livewire\Components\FeaturePolicy;
use Liberu\SocialNetwork\SocialCore\Livewire\Components\NetworkSettings;
use Liberu\SocialNetwork\SocialCore\Livewire\Components\SharedIds;
use Liberu\SocialNetwork\SocialCore\Livewire\Components\Terminology;
use Livewire\Livewire;

final class SocialCoreLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-social-core-livewire');

        Livewire::component('module-social-network-social-core::settings', Components\SocialCoreSettings::class);
        Livewire::component('module-social-network-social-core::network-settings', NetworkSettings::class);
        Livewire::component('module-social-network-social-core::deployment-mode', DeploymentMode::class);
        Livewire::component('module-social-network-social-core::terminology', Terminology::class);
        Livewire::component('module-social-network-social-core::feature-policy', FeaturePolicy::class);
        Livewire::component('module-social-network-social-core::shared-ids', SharedIds::class);
        Livewire::component('module-social-network-social-core::events', Events::class);
    }
}
