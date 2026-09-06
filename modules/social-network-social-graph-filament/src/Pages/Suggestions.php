<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Filament\Pages;

use Filament\Pages\Page;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\SocialGraph\Actions\SuggestProfiles;

final class Suggestions extends Page
{
    protected string $view = 'social-network-social-graph-filament::pages.suggestions';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    protected static string|\UnitEnum|null $navigationGroup = 'Social Network';

    /** @var array<int, array{id: string, handle: string}> */
    public array $suggestionRows = [];

    public function mount(GetProfile $get, SuggestProfiles $suggest): void
    {
        $this->suggestionRows = $suggest->for($get->forUser(auth()->id()))->map(fn ($profile): array => [
            'id' => (string) $profile->getKey(), 'handle' => (string) $profile->handle,
        ])->all();
    }
}
