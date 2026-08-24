<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Moderation\Filament;

use Illuminate\Support\ServiceProvider;

final class ModerationFilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-moderation-filament');
    }
}
