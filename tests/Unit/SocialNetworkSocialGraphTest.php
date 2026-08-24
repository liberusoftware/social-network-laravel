<?php

declare(strict_types=1);

use Illuminate\Contracts\Events\Dispatcher;
use InvalidArgumentException;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\SocialGraph\Actions\CreateRelationship;
use Liberu\SocialNetwork\SocialGraph\Actions\BlockProfile;
use Liberu\SocialNetwork\SocialGraph\Actions\UpdateRelationshipVisibility;
use Liberu\SocialNetwork\SocialGraph\Contracts\GraphAuthorizer;
use Liberu\SocialNetwork\SocialGraph\Models\Relationship;

it('rejects a profile attempting to relate to itself', function () {
    $authorizer = Mockery::mock(GraphAuthorizer::class);
    $events = Mockery::mock(Dispatcher::class);
    $profile = new Profile(['id' => 'profile-1']);

    expect(fn () => new CreateRelationship($authorizer, $events)->follow($profile, $profile))
        ->toThrow(InvalidArgumentException::class, 'cannot relate to itself');
});

it('rejects a profile attempting to block itself before persistence', function () {
    $authorizer = Mockery::mock(GraphAuthorizer::class);
    $profile = new Profile(['id' => 'profile-1']);

    expect(fn () => (new BlockProfile($authorizer))->handle($profile, $profile))
        ->toThrow(InvalidArgumentException::class, 'cannot block itself');
});

it('validates relationship visibility after authorization', function () {
    $authorizer = Mockery::mock(GraphAuthorizer::class);
    $authorizer->shouldReceive('visibility')->once();
    $actor = new Profile(['id' => 'profile-1', 'user_id' => 1]);
    $relationship = new Relationship(['source_profile_id' => 'profile-1']);

    expect(fn () => (new UpdateRelationshipVisibility($authorizer))->handle($actor, $relationship, 'hidden'))
        ->toThrow(InvalidArgumentException::class, 'visibility is not supported');
});
