<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialCore\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class SocialCoreFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-social-network-social-core-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([Pages\SocialCoreSettings::class]);
    }

    public function boot(Panel $panel): void {}
}
