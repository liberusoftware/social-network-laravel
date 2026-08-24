<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class NotificationsLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-notifications-livewire');
        Livewire::component('module-social-network-notifications::list', Components\ListNotifications::class);
    }
}
