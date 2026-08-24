<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Filament\Pages;

use Filament\Pages\Page;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\SocialGraph\Actions\ListOwnedLists;

final class Lists extends Page
{
    protected string $view = 'social-network-social-graph-filament::pages.lists';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';
    protected static string|\UnitEnum|null $navigationGroup = 'Social Network';

    /** @var array<int, array{id: string, name: string, visibility: string, profile_count: int}> */
    public array $listRows = [];

    public function mount(GetProfile $get, ListOwnedLists $lists): void
    {
        $this->listRows = $lists->handle($get->forUser(auth()->id()))->map(fn ($list): array => [
            'id' => (string) $list->getKey(),
            'name' => (string) $list->name,
            'visibility' => (string) $list->visibility,
            'profile_count' => $list->profiles->count(),
        ])->all();
    }
}
