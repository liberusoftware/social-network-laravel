<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class PublishingFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-social-network-publishing-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([Pages\Publications::class]);
    }

    public function boot(Panel $panel): void {}
}
