<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Communities\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Community extends Model
{
    use SoftDeletes;

    protected $table = 'social_communities';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'owner_profile_id', 'name', 'slug', 'description', 'visibility', 'rules'];

    protected function casts(): array
    {
        return ['rules' => 'array'];
    }
}
