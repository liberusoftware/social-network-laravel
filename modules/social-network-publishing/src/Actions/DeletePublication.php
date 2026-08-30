<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing\Actions;

use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\Publishing\Contracts\PublishingAuthorizer;
use Liberu\SocialNetwork\Publishing\Models\Publication;

final readonly class DeletePublication
{
    public function __construct(private PublishingAuthorizer $authorizer) {}

    public function handle(Profile $author, Publication $publication): void
    {
        $this->authorizer->update($author, $publication);
        $publication->delete();
    }
}
