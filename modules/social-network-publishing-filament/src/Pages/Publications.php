<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing\Filament\Pages;

use Filament\Pages\Page;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\Publishing\Models\Publication;

final class Publications extends Page
{
    protected string $view = 'social-network-publishing-filament::pages.publications';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Social Network';

    public function publications(GetProfile $get): mixed
    {
        abort_unless(auth()->check(), 404);
        return Publication::query()->where('author_profile_id', $get->forUser(auth()->id())->getKey())->latest()->limit(50)->get();
    }
}
