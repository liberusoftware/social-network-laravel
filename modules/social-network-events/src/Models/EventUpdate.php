<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Models;

use Illuminate\Database\Eloquent\Model;

final class EventUpdate extends Model
{
    protected $table = 'social_event_updates';

    protected $fillable = ['event_id', 'author_profile_id', 'body'];
}
