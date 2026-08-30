<?php

namespace Liberu\SocialNetwork\Federation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Liberu\SocialNetwork\Profiles\Models\Profile;

class FederationActor extends Model
{
    public $incrementing = false;

    protected $table = 'social_federation_actors';

    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = ['public_key' => 'array'];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }
}
