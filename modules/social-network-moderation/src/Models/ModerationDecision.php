<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Moderation\Models;

use Illuminate\Database\Eloquent\Model;

final class ModerationDecision extends Model
{
    protected $table = 'social_moderation_decisions';

    protected $fillable = ['report_id', 'actor_profile_id', 'action', 'reason', 'evidence'];

    protected $casts = ['evidence' => 'array'];
}
