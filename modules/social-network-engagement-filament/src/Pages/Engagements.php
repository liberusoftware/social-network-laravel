<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Engagement\Filament\Pages;

use Filament\Pages\Page;
use Liberu\SocialNetwork\Engagement\Models\Engagement;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;

final class Engagements extends Page
{
    protected string $view = 'social-network-engagement-filament::pages.engagements';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-heart';

    protected static string|\UnitEnum|null $navigationGroup = 'Social Network';

    public function engagements(GetProfile $get): mixed
    {
        abort_unless(auth()->check(), 404);

        return Engagement::query()->where('actor_profile_id', $get->forUser(auth()->id())->getKey())->latest()->limit(50)->get();
    }
}
