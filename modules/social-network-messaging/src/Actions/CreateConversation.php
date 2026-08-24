<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Liberu\SocialNetwork\Messaging\Contracts\MessagingAuthorizer;
use Liberu\SocialNetwork\Messaging\Models\Conversation;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class CreateConversation
{
    public function __construct(private MessagingAuthorizer $authorizer) {}

    public function handle(Profile $creator, ?string $title = null): Conversation
    {
        $this->authorizer->create($creator);

        return DB::transaction(function () use ($creator, $title): Conversation {
            $conversation = Conversation::query()->create(['id' => (string) Str::uuid(), 'created_by_profile_id' => $creator->getKey(), 'title' => $title, 'state' => 'active']);
            DB::table('social_conversation_members')->insert(['conversation_id' => $conversation->getKey(), 'profile_id' => $creator->getKey(), 'created_at' => now(), 'updated_at' => now()]);

            return $conversation;
        });
    }
}
