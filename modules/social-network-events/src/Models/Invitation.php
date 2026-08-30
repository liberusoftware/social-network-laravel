<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Models;

use Illuminate\Database\Eloquent\Model;

final class Invitation extends Model
{
    protected $table = 'social_event_invitations';

    protected $fillable = ['event_id', 'profile_id', 'state'];
}
