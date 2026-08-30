<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Models;

use Illuminate\Database\Eloquent\Model;

final class EventReminder extends Model
{
    protected $table = 'social_event_reminders';

    protected $fillable = ['event_id', 'profile_id', 'send_at', 'sent_at'];

    protected function casts(): array
    {
        return ['send_at' => 'datetime', 'sent_at' => 'datetime'];
    }
}
