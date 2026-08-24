<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications\Filament\Pages;

use Filament\Pages\Page;
use Liberu\SocialNetwork\Notifications\Models\SocialNotification;

final class Notifications extends Page
{
    protected string $view = 'social-network-notifications-filament::pages.notifications';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-bell';

    protected static string|\UnitEnum|null $navigationGroup = 'Social Network';

    public function notifications(): mixed
    {
        return SocialNotification::query()->latest()->limit(50)->get();
    }
}
