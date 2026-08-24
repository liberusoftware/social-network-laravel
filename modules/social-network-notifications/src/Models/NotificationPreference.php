<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications\Models;

use Illuminate\Database\Eloquent\Model;

final class NotificationPreference extends Model
{
    protected $table = 'social_notification_preferences';

    protected $fillable = ['profile_id', 'channels', 'quiet_hours', 'digest'];

    protected function casts(): array
    {
        return ['channels' => 'array', 'quiet_hours' => 'array', 'digest' => 'array'];
    }
}
