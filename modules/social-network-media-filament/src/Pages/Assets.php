<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Filament\Pages;

use Filament\Pages\Page;
use Liberu\SocialNetwork\Media\Models\MediaAsset;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;

final class Assets extends Page
{
    protected string $view = 'social-network-media-filament::pages.assets';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static string|\UnitEnum|null $navigationGroup = 'Social Network';

    public function assets(GetProfile $get): mixed
    {
        abort_unless(auth()->check(), 404);

        return MediaAsset::query()->where('owner_profile_id', $get->forUser(auth()->id())->getKey())->latest()->limit(50)->get();
    }
}
