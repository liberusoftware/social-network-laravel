<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications\Filament;

use Illuminate\Support\ServiceProvider;

final class NotificationsFilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-notifications-filament');
    }
}
