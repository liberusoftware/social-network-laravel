<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class SocialGraphFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-social-network-social-graph-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([Pages\Relationships::class, Pages\Blocks::class, Pages\Lists::class, Pages\Suggestions::class]);
    }

    public function boot(Panel $panel): void {}
}
