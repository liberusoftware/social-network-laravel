<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Contracts;

use Liberu\SocialNetwork\Profiles\Models\Profile;

interface MediaAuthorizer
{
    public function upload(Profile $owner): void;

    public function update(Profile $owner): void;
}
