<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Filament;

use Illuminate\Support\ServiceProvider;

final class EventsFilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-events-filament');
    }
}
