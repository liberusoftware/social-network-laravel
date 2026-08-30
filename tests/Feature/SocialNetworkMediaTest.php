<?php

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Liberu\SocialNetwork\Media\Actions\DeleteMediaAsset;
use Liberu\SocialNetwork\Media\Actions\MarkMediaReady;
use Liberu\SocialNetwork\Media\Actions\RegisterMediaAsset;
use Liberu\SocialNetwork\Media\Models\Album;
use Liberu\SocialNetwork\Media\Models\MediaAsset;
use Liberu\SocialNetwork\Profiles\Models\Profile;

it('registers, verifies, and deletes media through the configured filesystem', function (): void {
    config()->set('social-network-profiles.user_model', User::class);
    Storage::fake('public');
    $user = User::factory()->create();
    $profile = Profile::query()->create([
        'id' => (string) Str::uuid(),
        'user_id' => $user->getKey(),
        'handle' => 'media-'.strtolower(substr((string) $user->getKey(), 0, 8)),
    ]);
    Storage::disk('public')->put('uploads/photo.jpg', 'image bytes');

    $this->actingAs($user);
    $asset = app(RegisterMediaAsset::class)->handle($profile, [
        'type' => 'image',
        'disk' => 'public',
        'path' => 'uploads/photo.jpg',
        'alt_text' => 'A photo',
    ]);

    expect($asset->state)->toBe('pending')
        ->and($asset->size)->toBe(11);

    $asset = app(MarkMediaReady::class)->handle($profile, $asset);
    expect($asset->state)->toBe('ready')->and($asset->checksum)->toBeString();

    app(DeleteMediaAsset::class)->handle($profile, $asset);

    expect(Storage::disk('public')->exists('uploads/photo.jpg'))->toBeFalse()
        ->and(MediaAsset::withTrashed()->find($asset->getKey())?->trashed())->toBeTrue();
});

it('rejects media paths that escape the configured disk', function (): void {
    config()->set('social-network-profiles.user_model', User::class);
    Storage::fake('public');
    $user = User::factory()->create();
    $profile = Profile::query()->create([
        'id' => (string) Str::uuid(),
        'user_id' => $user->getKey(),
        'handle' => 'media-'.strtolower(substr((string) $user->getKey(), 0, 8)),
    ]);

    $this->actingAs($user);

    expect(fn (): mixed => app(RegisterMediaAsset::class)->handle($profile, [
        'type' => 'image',
        'disk' => 'public',
        'path' => '../private.jpg',
    ]))->toThrow(InvalidArgumentException::class);
});

it('provides privacy-aware album CRUD and keeps media when an album is deleted', function (): void {
    config()->set('social-network-profiles.user_model', User::class);
    $owner = User::factory()->create();
    $viewer = User::factory()->create();
    $ownerProfile = Profile::query()->create(['id' => (string) Str::uuid(), 'user_id' => $owner->getKey(), 'handle' => 'album-owner-'.strtolower(substr((string) $owner->getKey(), 0, 8))]);
    $viewerProfile = Profile::query()->create(['id' => (string) Str::uuid(), 'user_id' => $viewer->getKey(), 'handle' => 'album-viewer-'.strtolower(substr((string) $viewer->getKey(), 0, 8))]);

    $this->actingAs($owner);
    $response = $this->postJson('/api/v1/social-network/media/albums', ['name' => 'My Vacation', 'description' => 'Photos from summer', 'privacy' => 'public']);
    $response->assertCreated()->assertJsonPath('data.name', 'My Vacation');
    $album = Album::query()->where('name', 'My Vacation')->latest('created_at')->firstOrFail();
    expect($album->privacy)->toBe('public')
        ->and(Album::query()->whereKey($album->getKey())->visibleTo($viewerProfile)->exists())->toBeTrue();

    Storage::fake('public');
    Storage::disk('public')->put('album/photo.jpg', 'photo');
    $asset = app(RegisterMediaAsset::class)->handle($ownerProfile, ['type' => 'image', 'disk' => 'public', 'path' => 'album/photo.jpg', 'album_id' => $album->getKey()]);

    $this->actingAs($viewer)->getJson('/api/v1/social-network/media/albums/'.$album->getKey())->assertSuccessful();
    $this->actingAs($owner)->patchJson('/api/v1/social-network/media/albums/'.$album->getKey(), ['privacy' => 'private'])->assertSuccessful();
    $this->actingAs($viewer)->getJson('/api/v1/social-network/media/albums/'.$album->getKey())->assertForbidden();

    $this->actingAs($owner)->deleteJson('/api/v1/social-network/media/albums/'.$album->getKey())->assertNoContent();
    expect($asset->refresh()->album_id)->toBeNull();
});

it('rejects album creation without a name or with unsupported privacy', function (): void {
    config()->set('social-network-profiles.user_model', User::class);
    $user = User::factory()->create();
    Profile::query()->create(['id' => (string) Str::uuid(), 'user_id' => $user->getKey(), 'handle' => 'album-validation-'.strtolower(substr((string) $user->getKey(), 0, 8))]);

    $this->actingAs($user)->postJson('/api/v1/social-network/media/albums', ['privacy' => 'friends_only'])->assertJsonValidationErrors('name');
    $this->actingAs($user)->postJson('/api/v1/social-network/media/albums', ['name' => 'Album', 'privacy' => 'unknown'])->assertJsonValidationErrors('privacy');
});

it('allows owners to inspect and update media metadata', function (): void {
    config()->set('social-network-profiles.user_model', User::class);
    $owner = User::factory()->create();
    $viewer = User::factory()->create();
    $ownerProfile = Profile::query()->create(['id' => (string) Str::uuid(), 'user_id' => $owner->getKey(), 'handle' => 'media-show-'.strtolower(substr((string) $owner->getKey(), 0, 8))]);
    Profile::query()->create(['id' => (string) Str::uuid(), 'user_id' => $viewer->getKey(), 'handle' => 'media-view-'.strtolower(substr((string) $viewer->getKey(), 0, 8))]);
    Storage::disk('public')->put('media/metadata.jpg', 'photo');
    $this->actingAs($owner);
    $asset = app(RegisterMediaAsset::class)->handle($ownerProfile, ['type' => 'image', 'disk' => 'public', 'path' => 'media/metadata.jpg']);

    $this->actingAs($owner)->getJson('/api/v1/social-network/media/'.$asset->getKey())->assertSuccessful();
    $this->actingAs($owner)->patchJson('/api/v1/social-network/media/'.$asset->getKey(), ['alt_text' => 'A holiday photo', 'metadata' => ['source' => 'camera']])->assertSuccessful()->assertJsonPath('data.alt_text', 'A holiday photo');
    $this->actingAs($viewer)->getJson('/api/v1/social-network/media/'.$asset->getKey())->assertForbidden();
});
