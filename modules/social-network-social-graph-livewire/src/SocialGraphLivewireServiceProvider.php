<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class SocialGraphLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-social-graph-livewire');
        Livewire::component('module-social-network-social-graph::follow-profile', Components\FollowProfile::class);
        Livewire::component('module-social-network-social-graph::follow-friend-models', Components\FollowFriendModels::class);
        Livewire::component('module-social-network-social-graph::requests', Components\Requests::class);
        Livewire::component('module-social-network-social-graph::block-profile', Components\BlockProfile::class);
        Livewire::component('module-social-network-social-graph::lists', Components\ListManager::class);
        Livewire::component('module-social-network-social-graph::suggestions', Components\Suggestions::class);
        Livewire::component('module-social-network-social-graph::relationship-visibility', Components\RelationshipVisibility::class);
    }
}
