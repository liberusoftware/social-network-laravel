<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Feed\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class FeedFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-social-network-feed-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([Pages\Entries::class]);
    }

    public function boot(Panel $panel): void {}
}
