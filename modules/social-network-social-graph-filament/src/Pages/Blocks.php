<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Filament\Pages;

use Filament\Pages\Page;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\SocialGraph\Actions\ListOwnedBlocks;

final class Blocks extends Page
{
    protected string $view = 'social-network-social-graph-filament::pages.blocks';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-no-symbol';

    protected static string|\UnitEnum|null $navigationGroup = 'Social Network';

    /** @var array<int, array{id: string, target: string}> */
    public array $blockRows = [];

    public function mount(GetProfile $get, ListOwnedBlocks $list): void
    {
        $this->blockRows = $list->handle($get->forUser(auth()->id()))->map(fn ($block): array => [
            'id' => (string) $block->getKey(), 'target' => (string) $block->target_profile_id,
        ])->all();
    }
}
