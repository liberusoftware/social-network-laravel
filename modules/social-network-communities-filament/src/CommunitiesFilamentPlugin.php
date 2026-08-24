<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Communities\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class CommunitiesFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-social-network-communities-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([Pages\Communities::class]);
    }

    public function boot(Panel $panel): void {}
}
