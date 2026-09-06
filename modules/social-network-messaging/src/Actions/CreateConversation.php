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

    /** @param array<int, string> $participantIds */
    public function handle(Profile $creator, ?string $title = null, array $participantIds = []): Conversation
    {
        $this->authorizer->create($creator);

        return DB::transaction(function () use ($creator, $title, $participantIds): Conversation {
            $conversation = Conversation::query()->create(['id' => (string) Str::uuid(), 'created_by_profile_id' => $creator->getKey(), 'title' => $title, 'state' => 'active']);
            $members = collect($participantIds)->push((string) $creator->getKey())->unique()->map(fn (string $profileId): array => ['conversation_id' => $conversation->getKey(), 'profile_id' => $profileId, 'created_at' => now(), 'updated_at' => now()])->all();
            DB::table('social_conversation_members')->insert($members);

            return $conversation;
        });
    }
}
