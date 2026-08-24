<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Filament;

use Illuminate\Support\ServiceProvider;

final class MediaFilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-media-filament');
    }
}
