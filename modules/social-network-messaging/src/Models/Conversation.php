<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Models;

use Illuminate\Database\Eloquent\Model;

final class Conversation extends Model
{
    protected $table = 'social_conversations';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'created_by_profile_id', 'state', 'title'];
}
