<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class EventsFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-social-network-events-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([Pages\Events::class]);
    }

    public function boot(Panel $panel): void {}
}
