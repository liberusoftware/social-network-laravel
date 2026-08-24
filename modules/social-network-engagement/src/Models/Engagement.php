<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Engagement\Models;

use Illuminate\Database\Eloquent\Model;

final class Engagement extends Model
{
    protected $table = 'social_engagements';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'actor_profile_id', 'target_type', 'target_id', 'kind', 'reaction_type', 'body'];
}
