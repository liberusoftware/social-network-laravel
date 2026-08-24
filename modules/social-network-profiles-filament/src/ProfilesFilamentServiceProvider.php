<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Profiles\Filament;

use Illuminate\Support\ServiceProvider;

final class ProfilesFilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-profiles-filament');
    }
}
