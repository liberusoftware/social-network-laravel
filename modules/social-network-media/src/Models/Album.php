<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\SocialGraph\Models\Relationship;

final class Album extends Model
{
    use SoftDeletes;

    protected $table = 'social_media_albums';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'owner_profile_id', 'name', 'description', 'privacy', 'cover_path'];

    protected $attributes = ['privacy' => 'private'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'owner_profile_id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(MediaAsset::class, 'album_id');
    }

    public function scopeVisibleTo(Builder $query, ?Profile $viewer): Builder
    {
        if ($viewer === null) {
            return $query->where('privacy', 'public');
        }

        $relationships = (new Relationship())->getTable();
        $viewerId = (string) $viewer->getKey();

        return $query->where(function (Builder $query) use ($viewerId, $relationships): void {
            $query->where('privacy', 'public')
                ->orWhere('owner_profile_id', $viewerId)
                ->orWhere(function (Builder $query) use ($viewerId, $relationships): void {
                    $query->where('privacy', 'friends_only')
                        ->whereExists(function ($query) use ($viewerId, $relationships): void {
                            $query->selectRaw('1')
                                ->from($relationships)
                                ->where('relationship_type', 'friend')
                                ->where('status', 'accepted')
                                ->where(function ($query) use ($viewerId): void {
                                    $query->whereColumn('source_profile_id', 'social_media_albums.owner_profile_id')
                                        ->where('target_profile_id', $viewerId)
                                        ->orWhere(function ($query) use ($viewerId): void {
                                            $query->whereColumn('target_profile_id', 'social_media_albums.owner_profile_id')
                                                ->where('source_profile_id', $viewerId);
                                        });
                                });
                        });
                });
        });
    }
}
