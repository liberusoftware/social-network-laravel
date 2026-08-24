<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class MarkConversationRead
{
    public function handle(Profile $profile, string $conversationId): void
    {
        abort_unless(DB::table('social_conversation_members')->where(['conversation_id' => $conversationId, 'profile_id' => $profile->getKey()])->exists(), 403);
        DB::table('social_conversation_members')->where(['conversation_id' => $conversationId, 'profile_id' => $profile->getKey()])->update(['read_at' => now(), 'updated_at' => now()]);
    }
}
