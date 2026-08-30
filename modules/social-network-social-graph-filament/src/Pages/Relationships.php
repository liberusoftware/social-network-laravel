<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Filament\Pages;

use Filament\Pages\Page;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\SocialGraph\Actions\ListRelationships;

final class Relationships extends Page
{
    protected string $view = 'social-network-social-graph-filament::pages.relationships';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|\UnitEnum|null $navigationGroup = 'Social Network';

    /** @var array<int, array<string, mixed>> */
    public array $relationshipRows = [];

    public function mount(GetProfile $get, ListRelationships $list): void
    {
        $this->relationshipRows = $list->handle($get->forUser(auth()->id()))->map(fn ($relationship): array => [
            'type' => $relationship->relationship_type,
            'target' => $relationship->target_profile_id,
            'status' => $relationship->status,
            'visibility' => $relationship->visibility,
        ])->all();
    }

}
