<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\SocialNetwork\Messaging\Contracts\MessagingAuthorizer;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class RemoveParticipant
{
    public function __construct(private MessagingAuthorizer $authorizer) {}

    public function handle(Profile $actor, string $conversationId, Profile $participant): void
    {
        $this->authorizer->create($actor);
        $conversation = DB::table('social_conversations')->where('id', $conversationId)->first();
        abort_unless($conversation !== null && DB::table('social_conversation_members')->where(['conversation_id' => $conversationId, 'profile_id' => $actor->getKey()])->exists(), 403);
        abort_unless((string) $participant->getKey() === (string) $actor->getKey() || (string) $conversation->created_by_profile_id === (string) $actor->getKey(), 403);
        DB::table('social_conversation_members')->where(['conversation_id' => $conversationId, 'profile_id' => $participant->getKey()])->delete();
    }
}
