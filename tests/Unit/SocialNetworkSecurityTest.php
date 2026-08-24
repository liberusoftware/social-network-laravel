<?php

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Media\Actions\RegisterMediaAsset;
use Liberu\SocialNetwork\Media\Contracts\MediaAuthorizer;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\Publishing\Actions\PublishPublication;
use Liberu\SocialNetwork\Publishing\Contracts\PublishingAuthorizer;
use Liberu\SocialNetwork\Publishing\Models\Publication;
use Liberu\SocialNetwork\SocialGraph\Actions\AcceptFriendship;
use Liberu\SocialNetwork\SocialGraph\Models\Relationship;
use Symfony\Component\HttpKernel\Exception\HttpException;

it('prevents a profile from publishing another profile publication', function () {
    $authorizer = Mockery::mock(PublishingAuthorizer::class);
    $authorizer->shouldReceive('publish')->once();
    $events = Mockery::mock(Dispatcher::class);
    $author = new Profile(['id' => 'author-profile']);
    $publication = new Publication(['id' => 'publication-1', 'author_profile_id' => 'other-profile']);

    expect(fn () => (new PublishPublication($authorizer, $events))->handle($author, $publication))
        ->toThrow(HttpException::class);
});

it('prevents accepting a friendship for a different target profile', function () {
    Gate::shouldReceive('authorize')->once();
    $actor = new Profile(['id' => 'target-profile']);
    $relationship = new Relationship(['target_profile_id' => 'other-profile', 'relationship_type' => 'friend', 'status' => 'pending']);

    expect(fn () => (new AcceptFriendship())->handle($actor, $relationship))
        ->toThrow(HttpException::class);
});

it('rejects media paths that escape their configured disk', function () {
    $authorizer = Mockery::mock(MediaAuthorizer::class);
    $authorizer->shouldReceive('upload')->once();
    $events = Mockery::mock(Dispatcher::class);
    $owner = new Profile(['id' => 'owner-profile']);

    expect(fn () => (new RegisterMediaAsset($authorizer, $events))->handle($owner, ['type' => 'image', 'path' => '../private/secret.jpg']))
        ->toThrow(InvalidArgumentException::class);
});

it('rejects media assets on unconfigured disks', function () {
    $authorizer = Mockery::mock(MediaAuthorizer::class);
    $authorizer->shouldReceive('upload')->once();
    $events = Mockery::mock(Dispatcher::class);
    $owner = new Profile(['id' => 'owner-profile']);

    expect(fn () => (new RegisterMediaAsset($authorizer, $events))->handle($owner, ['type' => 'image', 'disk' => 'secret', 'path' => 'uploads/photo.jpg']))
        ->toThrow(InvalidArgumentException::class);
});
