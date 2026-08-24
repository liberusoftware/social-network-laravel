<?php

use Illuminate\Contracts\Events\Dispatcher;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\Publishing\Actions\PublishPublication;
use Liberu\SocialNetwork\Publishing\Contracts\PublishingAuthorizer;
use Liberu\SocialNetwork\Publishing\Models\Publication;
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
