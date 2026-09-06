<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Contracts;

use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\SocialGraph\Models\Relationship;

interface GraphAuthorizer
{
    public function follow(Profile $source, Profile $target): void;

    public function friend(Profile $source, Profile $target): void;

    public function list(Profile $owner): void;

    public function block(Profile $source, Profile $target): void;

    public function unblock(Profile $source, Profile $target): void;

    public function visibility(Profile $actor, Relationship $relationship): void;
}
