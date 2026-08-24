<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Communities\Models;

use Illuminate\Database\Eloquent\Model;

final class Membership extends Model
{
    protected $table = 'social_community_memberships';

    protected $fillable = ['community_id', 'profile_id', 'role', 'status'];
}
