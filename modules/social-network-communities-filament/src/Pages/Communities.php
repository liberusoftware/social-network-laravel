<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Communities\Filament\Pages;

use Filament\Pages\Page;
use Liberu\SocialNetwork\Communities\Actions\ListCommunities;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;

final class Communities extends Page
{
    protected string $view = 'social-network-communities-filament::pages.communities';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|\UnitEnum|null $navigationGroup = 'Social Network';

    public function communities(GetProfile $get, ListCommunities $list): mixed
    {
        abort_unless(auth()->check(), 404);

        return $list->handle($get->forUser(auth()->id()), 50);
    }
}
