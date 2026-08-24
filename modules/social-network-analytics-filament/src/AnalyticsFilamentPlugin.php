<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Analytics\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class AnalyticsFilamentPlugin implements Plugin
{
    public static function make(): self { return new self(); }

    public function getId(): string { return 'module-social-network-analytics-filament'; }

    public function register(Panel $panel): void { $panel->pages([Pages\Dashboard::class]); }

    public function boot(Panel $panel): void {}
}
