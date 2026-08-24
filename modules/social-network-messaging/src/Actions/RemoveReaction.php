<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\SocialNetwork\Messaging\Contracts\MessagingAuthorizer;
use Liberu\SocialNetwork\Messaging\Models\Message;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class RemoveReaction
{
    public function __construct(private MessagingAuthorizer $authorizer) {}

    public function handle(Profile $profile, Message $message, string $emoji): void
    {
        $this->authorizer->send($profile);
        abort_unless(DB::table('social_conversation_members')->where([
            'conversation_id' => $message->conversation_id,
            'profile_id' => $profile->getKey(),
        ])->exists(), 403);
        DB::table('social_message_reactions')->where([
            'message_id' => $message->getKey(),
            'profile_id' => $profile->getKey(),
            'emoji' => $emoji,
        ])->delete();
    }
}
