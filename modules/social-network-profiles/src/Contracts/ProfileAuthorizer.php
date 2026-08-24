<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Profiles\Contracts;

use Liberu\SocialNetwork\Profiles\Models\Profile;

interface ProfileAuthorizer
{
    public function view(Profile $profile): void;

    public function update(Profile $profile): void;

    public function block(Profile $profile, Profile $target): void;
}
