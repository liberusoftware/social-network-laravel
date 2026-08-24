<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing\Filament;

use Illuminate\Support\ServiceProvider;

final class PublishingFilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-publishing-filament');
    }
}
