<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Message extends Model
{
    use SoftDeletes;

    protected $table = 'social_messages';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'conversation_id', 'sender_profile_id', 'body', 'state', 'attachments'];

    protected function casts(): array
    {
        return ['attachments' => 'array'];
    }
}
