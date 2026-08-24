<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Engagement\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class EngagementFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-social-network-engagement-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([Pages\Engagements::class]);
    }

    public function boot(Panel $panel): void {}
}
