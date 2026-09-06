<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class Conversation extends Model
{
    protected $table = 'social_conversations';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'created_by_profile_id', 'state', 'title'];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class, 'social_conversation_members', 'conversation_id', 'profile_id')->withPivot('read_at')->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'conversation_id');
    }
}
