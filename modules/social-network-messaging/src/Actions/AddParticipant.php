<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\SocialNetwork\Messaging\Contracts\MessagingAuthorizer;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class AddParticipant
{
    public function __construct(private MessagingAuthorizer $authorizer) {}

    public function handle(Profile $actor, string $conversationId, Profile $participant): void
    {
        $this->authorizer->create($actor);
        abort_unless(DB::table('social_conversation_members')->where(['conversation_id' => $conversationId, 'profile_id' => $actor->getKey()])->exists(), 403);
        DB::table('social_conversation_members')->insertOrIgnore(['conversation_id' => $conversationId, 'profile_id' => $participant->getKey(), 'created_at' => now(), 'updated_at' => now()]);
    }
}
