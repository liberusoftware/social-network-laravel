<?php

declare(strict_types=1);

use Illuminate\Contracts\Events\Dispatcher;
use InvalidArgumentException;
use Liberu\SocialNetwork\Profiles\Actions\BlockProfile;
use Liberu\SocialNetwork\Profiles\Actions\UpdateProfile;
use Liberu\SocialNetwork\Profiles\Contracts\ProfileAuthorizer;
use Liberu\SocialNetwork\Profiles\Contracts\ProfileRepository;
use Liberu\SocialNetwork\Profiles\Models\Profile;

it('rejects a profile attempting to block itself', function () {
    $profile = new Profile(['id' => 'profile-1']);

    expect(fn () => app(BlockProfile::class)->handle($profile, $profile))
        ->toThrow(InvalidArgumentException::class, 'cannot block itself');
});

it('rejects invalid handles before persistence', function () {
    $authorizer = Mockery::mock(ProfileAuthorizer::class);
    $authorizer->shouldReceive('update')->once();
    $repository = Mockery::mock(ProfileRepository::class);
    $events = Mockery::mock(Dispatcher::class);

    expect(fn () => new UpdateProfile($repository, $authorizer, $events)->handle(
        new Profile(['id' => 'profile-1', 'handle' => 'valid_handle']),
        ['handle' => 'invalid-handle'],
    ))->toThrow(InvalidArgumentException::class, 'only letters');
});
