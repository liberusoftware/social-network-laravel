<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications\Models;

use Illuminate\Database\Eloquent\Model;

final class SocialNotification extends Model
{
    protected $table = 'social_notifications';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'profile_id', 'kind', 'group_key', 'state', 'channel', 'payload', 'read_at'];

    protected function casts(): array
    {
        return ['payload' => 'array', 'read_at' => 'datetime'];
    }
}
