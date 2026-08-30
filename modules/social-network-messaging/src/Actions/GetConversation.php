<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\SocialNetwork\Messaging\Models\Conversation;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class GetConversation
{
    public function handle(Profile $viewer, string $conversationId): Conversation
    {
        abort_unless(DB::table('social_conversation_members')->where(['conversation_id' => $conversationId, 'profile_id' => $viewer->getKey()])->exists(), 403);

        return Conversation::query()->with(['members', 'messages' => fn ($query) => $query->latest()])->findOrFail($conversationId);
    }
}
