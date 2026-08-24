<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing\Contracts;

use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\Publishing\Models\Publication;

interface PublishingAuthorizer
{
    public function create(Profile $author): void;

    public function update(Profile $author, Publication $publication): void;

    public function publish(Profile $author, Publication $publication): void;
}
