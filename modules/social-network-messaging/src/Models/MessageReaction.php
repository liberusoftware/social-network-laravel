<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Models;

use Illuminate\Database\Eloquent\Model;

final class MessageReaction extends Model
{
    protected $table = 'social_message_reactions';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'message_id', 'conversation_id', 'profile_id', 'emoji'];
}
