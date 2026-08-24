<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Discovery\Models;

use Illuminate\Database\Eloquent\Model;

final class DiscoveryIndex extends Model
{
    protected $table = 'social_discovery_index';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'resource_type', 'resource_id', 'owner_profile_id', 'visibility', 'body', 'terms', 'engagement_score', 'published_at'];
    protected function casts(): array { return ['terms' => 'array', 'published_at' => 'datetime', 'engagement_score' => 'integer']; }
}
