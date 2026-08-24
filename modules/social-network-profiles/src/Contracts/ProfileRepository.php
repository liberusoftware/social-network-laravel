<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Profiles\Contracts;

use Liberu\SocialNetwork\Profiles\Models\Profile;

interface ProfileRepository
{
    public function forUser(int|string $userId): Profile;

    public function find(string $id): Profile;
}
