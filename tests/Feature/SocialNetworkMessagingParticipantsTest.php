<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Liberu\SocialNetwork\Profiles\Models\Profile;

it('creates conversations with participants and manages membership', function (): void {
    config()->set('social-network-profiles.user_model', User::class);
    $creator = User::factory()->create();
    $participant = User::factory()->create();
    $newParticipant = User::factory()->create();
    $profiles = collect([$creator, $participant, $newParticipant])->mapWithKeys(fn (User $user): array => [$user->getKey() => Profile::query()->create(['id' => (string) Str::uuid(), 'user_id' => $user->getKey(), 'handle' => 'message-'.strtolower(substr((string) $user->getKey(), 0, 8))])]);

    $this->actingAs($creator);
    $conversation = $this->postJson('/api/v1/social-network/messaging/conversations', ['title' => 'Project', 'participant_profile_ids' => [$profiles[$participant->getKey()]->getKey()]])->assertCreated()->json('data.id');

    expect(DB::table('social_conversation_members')->where('conversation_id', $conversation)->count())->toBe(2);

    $message = $this->postJson('/api/v1/social-network/messaging/conversations/'.$conversation.'/messages', ['body' => 'Hello'])->assertCreated()->json('data.id');
    $this->getJson('/api/v1/social-network/messaging/conversations/'.$conversation)
        ->assertSuccessful()
        ->assertJsonPath('data.messages.0.id', $message)
        ->assertJsonCount(2, 'data.participants');

    $this->postJson('/api/v1/social-network/messaging/conversations/'.$conversation.'/participants', ['profile_id' => $profiles[$newParticipant->getKey()]->getKey()])->assertNoContent();
    expect(DB::table('social_conversation_members')->where(['conversation_id' => $conversation, 'profile_id' => $profiles[$newParticipant->getKey()]->getKey()])->exists())->toBeTrue();

    $this->deleteJson('/api/v1/social-network/messaging/conversations/'.$conversation.'/participants/'.$profiles[$newParticipant->getKey()]->getKey())->assertNoContent();
    expect(DB::table('social_conversation_members')->where(['conversation_id' => $conversation, 'profile_id' => $profiles[$newParticipant->getKey()]->getKey()])->exists())->toBeFalse();

    $this->deleteJson('/api/v1/social-network/messaging/conversations/'.$conversation.'/messages/'.$message)->assertNoContent();
});

it('reports unread messages and clears them when the conversation is read', function (): void {
    config()->set('social-network-profiles.user_model', User::class);
    $sender = User::factory()->create();
    $recipient = User::factory()->create();
    $profiles = collect([$sender, $recipient])->mapWithKeys(fn (User $user): array => [$user->getKey() => Profile::query()->create(['id' => (string) Str::uuid(), 'user_id' => $user->getKey(), 'handle' => 'unread-'.strtolower(substr((string) $user->getKey(), 0, 8))])]);

    $this->actingAs($sender);
    $conversation = $this->postJson('/api/v1/social-network/messaging/conversations', ['participant_profile_ids' => [$profiles[$recipient->getKey()]->getKey()]])->assertCreated()->json('data.id');
    $this->postJson('/api/v1/social-network/messaging/conversations/'.$conversation.'/messages', ['body' => 'Unread'])->assertCreated();

    $this->actingAs($recipient)->getJson('/api/v1/social-network/messaging/unread-count')
        ->assertSuccessful()
        ->assertJsonPath('count', 1);
    $this->postJson('/api/v1/social-network/messaging/conversations/'.$conversation.'/read')->assertNoContent();
    $this->getJson('/api/v1/social-network/messaging/unread-count')->assertJsonPath('count', 0);
});
