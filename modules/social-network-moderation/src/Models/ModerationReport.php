<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Moderation\Models;

use Illuminate\Database\Eloquent\Model;

final class ModerationReport extends Model
{
    protected $table = 'social_moderation_reports';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'reporter_profile_id', 'target_type', 'target_id', 'reason', 'details', 'state', 'assigned_to'];

    protected function casts(): array
    {
        return ['created_at' => 'datetime', 'updated_at' => 'datetime'];
    }
}
