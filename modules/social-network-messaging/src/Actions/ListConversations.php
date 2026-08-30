<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Actions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Liberu\SocialNetwork\Messaging\Models\Conversation;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class ListConversations
{
    public function handle(Profile $profile, int $limit = 25): Collection
    {
        $ids = DB::table('social_conversation_members')->where('profile_id', $profile->getKey())->pluck('conversation_id');

        return Conversation::query()->whereIn('id', $ids)->latest()->limit(max(1, min($limit, 100)))->get();
    }
}
