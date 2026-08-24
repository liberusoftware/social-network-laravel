<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Engagement\Filament\Pages;

use Filament\Pages\Page;
use Liberu\SocialNetwork\Engagement\Models\Engagement;

final class Engagements extends Page
{
    protected string $view = 'social-network-engagement-filament::pages.engagements';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-heart';

    protected static string|\UnitEnum|null $navigationGroup = 'Social Network';

    public function engagements(): mixed
    {
        return Engagement::query()->latest()->limit(50)->get();
    }
}
