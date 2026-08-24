<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Communities\Filament\Pages;

use Filament\Pages\Page;
use Liberu\SocialNetwork\Communities\Models\Community;

final class Communities extends Page
{
    protected string $view = 'social-network-communities-filament::pages.communities';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|\UnitEnum|null $navigationGroup = 'Social Network';

    public function communities(): mixed
    {
        return Community::query()->latest()->limit(50)->get();
    }
}
