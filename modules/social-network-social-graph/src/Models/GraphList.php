<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Models;

use Illuminate\Database\Eloquent\Model;

final class GraphList extends Model
{
    protected $table = 'social_graph_lists';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'owner_profile_id', 'name', 'visibility'];
}
