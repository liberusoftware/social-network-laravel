<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Livewire;

use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\Messaging\Livewire\Components\Conversations;
use Liberu\SocialNetwork\Messaging\Livewire\Components\Messages;
use Livewire\Livewire;

final class MessagingLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-messaging-livewire');
        Livewire::component('module-social-network-messaging::composer', Components\Composer::class);
        Livewire::component('module-social-network-messaging::conversations', Conversations::class);
        Livewire::component('module-social-network-messaging::messages', Messages::class);
    }
}
