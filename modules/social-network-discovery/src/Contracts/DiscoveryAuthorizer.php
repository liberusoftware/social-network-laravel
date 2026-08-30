<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Discovery\Contracts;

use Liberu\SocialNetwork\Profiles\Models\Profile;

interface DiscoveryAuthorizer
{
    public function search(Profile $viewer): void;

    public function index(Profile $owner): void;
}
