<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Discovery\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class DiscoveryFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-social-network-discovery-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([Pages\Search::class]);
    }

    public function boot(Panel $panel): void {}
}
