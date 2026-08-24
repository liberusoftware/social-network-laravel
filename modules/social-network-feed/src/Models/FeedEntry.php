<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Feed\Models;

use Illuminate\Database\Eloquent\Model;

final class FeedEntry extends Model
{
    protected $table = 'social_feed_entries';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'viewer_profile_id', 'item_type', 'item_id', 'rank', 'visible_at'];

    protected function casts(): array
    {
        return ['rank' => 'float', 'visible_at' => 'datetime'];
    }
}
