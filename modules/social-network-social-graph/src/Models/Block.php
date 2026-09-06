<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Models;

use Illuminate\Database\Eloquent\Model;

final class Block extends Model
{
    protected $table = 'social_graph_blocks';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'source_profile_id', 'target_profile_id'];
}
