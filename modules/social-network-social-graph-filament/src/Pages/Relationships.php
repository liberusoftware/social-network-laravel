<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Filament\Pages;

use Filament\Pages\Page;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\SocialGraph\Models\Relationship;

final class Relationships extends Page
{
    protected string $view = 'social-network-social-graph-filament::pages.relationships';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|\UnitEnum|null $navigationGroup = 'Social Network';

    public function relationships(): mixed
    {
        $p = app(GetProfile::class)->forUser(auth()->id());

        return Relationship::query()->where('source_profile_id', $p->getKey())->latest()->get();
    }
}
