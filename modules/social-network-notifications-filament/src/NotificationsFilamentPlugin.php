<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class NotificationsFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-social-network-notifications-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([Pages\Notifications::class]);
    }

    public function boot(Panel $panel): void {}
}
