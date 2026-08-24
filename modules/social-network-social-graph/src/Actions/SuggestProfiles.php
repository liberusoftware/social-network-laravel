<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Actions;

use Illuminate\Database\Eloquent\Collection;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\SocialGraph\Models\Block;
use Liberu\SocialNetwork\SocialGraph\Models\Relationship;

final class SuggestProfiles
{
    public function for(Profile $profile, int $limit = 10): Collection
    {
        $profilesTable = (new Profile())->getTable();
        $blocksTable = (new Block())->getTable();
        $relationshipsTable = (new Relationship())->getTable();

        return Profile::query()
            ->whereKeyNot($profile->getKey())
            ->where('lifecycle_state', 'active')
            ->where('visibility', 'public')
            ->whereNotExists(function ($query) use ($profile, $blocksTable, $profilesTable): void {
                $query->selectRaw('1')->from($blocksTable.' as suggestion_block')
                    ->where(function ($query) use ($profile, $profilesTable): void {
                        $query->where(function ($query) use ($profile, $profilesTable): void {
                            $query->where('suggestion_block.source_profile_id', $profile->getKey())
                                ->whereColumn('suggestion_block.target_profile_id', $profilesTable.'.id');
                        })->orWhere(function ($query) use ($profile, $profilesTable): void {
                            $query->whereColumn('suggestion_block.source_profile_id', $profilesTable.'.id')
                                ->where('suggestion_block.target_profile_id', $profile->getKey());
                        });
                    });
            })
            ->whereNotExists(function ($query) use ($profile, $relationshipsTable, $profilesTable): void {
                $query->selectRaw('1')->from($relationshipsTable.' as suggestion_relationship')
                    ->where('suggestion_relationship.source_profile_id', $profile->getKey())
                    ->whereColumn('suggestion_relationship.target_profile_id', $profilesTable.'.id')
                    ->whereIn('suggestion_relationship.relationship_type', ['follow', 'friend'])
                    ->whereIn('suggestion_relationship.status', ['pending', 'accepted']);
            })
            ->limit(min(max($limit, 1), 50))
            ->get();
    }
}
