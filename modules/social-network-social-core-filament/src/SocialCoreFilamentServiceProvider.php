<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialCore\Filament;

use Illuminate\Support\ServiceProvider;

final class SocialCoreFilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-social-core-filament');
    }
}
