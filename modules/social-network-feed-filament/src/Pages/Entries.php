<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Feed\Filament\Pages;

use Filament\Pages\Page;
use Liberu\SocialNetwork\Feed\Models\FeedEntry;

final class Entries extends Page
{
    protected string $view = 'social-network-feed-filament::pages.entries';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'Social Network';

    public function entries(): mixed
    {
        return FeedEntry::query()->latest()->limit(50)->get();
    }
}
