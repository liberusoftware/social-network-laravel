<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Moderation\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class ModerationFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-social-network-moderation-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([Pages\Reports::class]);
    }

    public function boot(Panel $panel): void {}
}
