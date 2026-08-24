<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Moderation\Filament\Pages;

use Filament\Pages\Page;
use Liberu\SocialNetwork\Moderation\Models\ModerationReport;

final class Reports extends Page
{
    protected string $view = 'social-network-moderation-filament::pages.reports';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-exclamation';

    protected static string|\UnitEnum|null $navigationGroup = 'Social Network';

    public function reports(): mixed
    {
        return ModerationReport::query()->latest()->limit(50)->get();
    }
}
