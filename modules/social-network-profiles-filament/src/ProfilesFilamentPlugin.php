<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Profiles\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class ProfilesFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-social-network-profiles-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([Pages\EditProfile::class]);
    }

    public function boot(Panel $panel): void {}
}
