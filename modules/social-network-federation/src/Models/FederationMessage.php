<?php

namespace Liberu\SocialNetwork\Federation\Models;

use Illuminate\Database\Eloquent\Model;

class FederationMessage extends Model
{
    public $incrementing = false;

    protected $table = 'social_federation_messages';

    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = ['payload' => 'array', 'received_at' => 'datetime'];
}
