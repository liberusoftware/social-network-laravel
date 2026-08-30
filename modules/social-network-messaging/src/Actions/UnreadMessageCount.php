<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class UnreadMessageCount
{
    public function handle(Profile $profile): int
    {
        return (int) DB::table('social_messages as messages')
            ->join('social_conversation_members as members', 'members.conversation_id', '=', 'messages.conversation_id')
            ->where('members.profile_id', $profile->getKey())
            ->where('messages.sender_profile_id', '!=', $profile->getKey())
            ->whereNull('messages.deleted_at')
            ->where(function ($query): void {
                $query->whereNull('members.read_at')
                    ->orWhereColumn('messages.created_at', '>', 'members.read_at');
            })
            ->count('messages.id');
    }
}
