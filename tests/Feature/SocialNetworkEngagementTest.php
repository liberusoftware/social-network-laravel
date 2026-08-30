<?php

use App\Models\User;
use Illuminate\Support\Str;
use Liberu\SocialNetwork\Engagement\Models\Engagement;
use Liberu\SocialNetwork\Profiles\Models\Profile;

it('lists engagement history and permits comment edits by their author', function (): void {
    config()->set('social-network-profiles.user_model', User::class);
    $user = User::factory()->create();
    Profile::query()->create(['id' => (string) Str::uuid(), 'user_id' => $user->getKey(), 'handle' => 'engagement-'.strtolower(substr((string) $user->getKey(), 0, 8))]);
    $targetId = (string) Str::uuid();

    $this->actingAs($user);
    $first = $this->postJson('/api/v1/social-network/engagement', ['kind' => 'comment', 'target_type' => 'publication', 'target_id' => $targetId, 'body' => 'First comment'])->assertCreated();
    $this->postJson('/api/v1/social-network/engagement', ['kind' => 'comment', 'target_type' => 'publication', 'target_id' => $targetId, 'body' => 'Second comment'])->assertCreated();

    $this->getJson('/api/v1/social-network/engagement/target/publication/'.$targetId)
        ->assertSuccessful()
        ->assertJsonPath('count', 2)
        ->assertJsonCount(2, 'data');

    $id = $first->json('data.id');
    $this->patchJson('/api/v1/social-network/engagement/'.$id, ['body' => 'Edited comment'])
        ->assertSuccessful()
        ->assertJsonPath('data.body', 'Edited comment');

    expect(Engagement::query()->find($id)->body)->toBe('Edited comment');
});
