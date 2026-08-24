<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class MessagingFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-social-network-messaging-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([Pages\Conversations::class]);
    }

    public function boot(Panel $panel): void {}
}
