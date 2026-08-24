<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Communities\Filament;

use Illuminate\Support\ServiceProvider;

final class CommunitiesFilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-communities-filament');
    }
}
