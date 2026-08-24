<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Publication extends Model
{
    use SoftDeletes;

    protected $table = 'social_publications';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'author_profile_id', 'kind', 'state', 'audience', 'title', 'body', 'metadata', 'scheduled_at', 'published_at'];

    protected function casts(): array
    {
        return ['metadata' => 'array', 'scheduled_at' => 'datetime', 'published_at' => 'datetime'];
    }
}
