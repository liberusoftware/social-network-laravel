<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Event extends Model
{
    use SoftDeletes;

    protected $table = 'social_events';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'owner_profile_id', 'state', 'visibility', 'timezone', 'title', 'description', 'starts_at', 'ends_at', 'capacity', 'location', 'metadata'];

    protected function casts(): array
    {
        return ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'location' => 'array', 'metadata' => 'array', 'capacity' => 'integer'];
    }
}
