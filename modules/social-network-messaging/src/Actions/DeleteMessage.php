<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\SocialNetwork\Messaging\Contracts\MessagingAuthorizer;
use Liberu\SocialNetwork\Messaging\Models\Message;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class DeleteMessage
{
    public function __construct(private MessagingAuthorizer $authorizer) {}

    public function handle(Profile $actor, string $conversationId, Message $message): void
    {
        $this->authorizer->send($actor);
        abort_unless((string) $message->conversation_id === $conversationId, 404);
        abort_unless(DB::table('social_conversation_members')->where(['conversation_id' => $conversationId, 'profile_id' => $actor->getKey()])->exists(), 403);
        abort_unless((string) $message->sender_profile_id === (string) $actor->getKey(), 403);
        $message->delete();
    }
}
