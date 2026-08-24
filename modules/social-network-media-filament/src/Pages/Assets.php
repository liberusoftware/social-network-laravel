<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Filament\Pages;

use Filament\Pages\Page;
use Liberu\SocialNetwork\Media\Models\MediaAsset;

final class Assets extends Page
{
    protected string $view = 'social-network-media-filament::pages.assets';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static string|\UnitEnum|null $navigationGroup = 'Social Network';

    public function assets(): mixed
    {
        return MediaAsset::query()->latest()->limit(50)->get();
    }
}
