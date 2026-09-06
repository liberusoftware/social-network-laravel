<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final class Relationship extends Model
{
    protected $table = 'social_graph_relationships';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'source_profile_id', 'target_profile_id', 'relationship_type', 'status', 'visibility'];

    protected $attributes = [
        'status' => 'accepted',
        'visibility' => 'followers',
    ];

    public function scopeVisibleTo(Builder $query, string $profileId): Builder
    {
        $relationshipTable = (new self())->getTable();
        $blockTable = (new Block())->getTable();

        return $query->whereNotExists(function ($query) use ($profileId, $relationshipTable, $blockTable): void {
            $query->selectRaw('1')->from($blockTable.' as viewer_block')
                ->where(function ($query) use ($profileId, $relationshipTable): void {
                    $query->where(function ($query) use ($profileId, $relationshipTable): void {
                        $query->where('viewer_block.source_profile_id', $profileId)
                            ->whereColumn('viewer_block.target_profile_id', $relationshipTable.'.source_profile_id');
                    })->orWhere(function ($query) use ($profileId, $relationshipTable): void {
                        $query->whereColumn('viewer_block.source_profile_id', $relationshipTable.'.source_profile_id')
                            ->where('viewer_block.target_profile_id', $profileId);
                    });
                });
        })->where(function (Builder $query) use ($profileId, $relationshipTable): void {
            $query->where('source_profile_id', $profileId)
                ->orWhere(function (Builder $query) use ($profileId, $relationshipTable): void {
                    $query->where('visibility', 'public')
                        ->orWhere(function (Builder $query) use ($profileId, $relationshipTable): void {
                            $query->where('visibility', 'followers')
                                ->whereExists(function ($query) use ($profileId, $relationshipTable): void {
                                    $query->selectRaw('1')
                                        ->from($relationshipTable.' as viewer_relationship')
                                        ->where('viewer_relationship.source_profile_id', $profileId)
                                        ->whereColumn('viewer_relationship.target_profile_id', $relationshipTable.'.source_profile_id')
                                        ->where('viewer_relationship.relationship_type', 'follow')
                                        ->where('viewer_relationship.status', 'accepted');
                                });
                        });
                });
        });
    }
}
