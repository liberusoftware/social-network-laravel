<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class MediaFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-social-network-media-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([Pages\Assets::class]);
    }

    public function boot(Panel $panel): void {}
}
