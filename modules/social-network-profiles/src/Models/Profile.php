<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Profiles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Profile extends Model
{
    use SoftDeletes;

    protected $table = 'social_profiles';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id', 'user_id', 'handle', 'bio', 'attributes', 'avatar_path', 'verification_status', 'visibility', 'lifecycle_state',
    ];

    protected function casts(): array
    {
        return ['attributes' => 'array'];
    }

    /** @return BelongsTo<Model, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('social-network-profiles.user_model'));
    }

    /** @return BelongsToMany<Profile, $this> */
    public function blockedProfiles(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'social_profile_blocks', 'blocker_profile_id', 'blocked_profile_id')->withTimestamps();
    }

    /** @return BelongsToMany<Profile, $this> */
    public function blockedByProfiles(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'social_profile_blocks', 'blocked_profile_id', 'blocker_profile_id')->withTimestamps();
    }
}
