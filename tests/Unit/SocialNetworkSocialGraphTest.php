<?php

declare(strict_types=1);

use Illuminate\Contracts\Events\Dispatcher;
use InvalidArgumentException;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\SocialGraph\Actions\CreateRelationship;
use Liberu\SocialNetwork\SocialGraph\Contracts\GraphAuthorizer;

it('rejects a profile attempting to relate to itself', function () {
    $authorizer = Mockery::mock(GraphAuthorizer::class);
    $events = Mockery::mock(Dispatcher::class);
    $profile = new Profile(['id' => 'profile-1']);

    expect(fn () => new CreateRelationship($authorizer, $events)->follow($profile, $profile))
        ->toThrow(InvalidArgumentException::class, 'cannot relate to itself');
});
