<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Feed\Filament;

use Illuminate\Support\ServiceProvider;

final class FeedFilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-feed-filament');
    }
}
