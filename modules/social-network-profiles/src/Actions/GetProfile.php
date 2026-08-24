<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Profiles\Actions;

use Liberu\SocialNetwork\Profiles\Contracts\ProfileAuthorizer;
use Liberu\SocialNetwork\Profiles\Contracts\ProfileRepository;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class GetProfile
{
    public function __construct(private ProfileRepository $profiles, private ProfileAuthorizer $authorizer) {}

    public function byId(string $id): Profile
    {
        $profile = $this->profiles->find($id);
        $this->authorizer->view($profile);

        return $profile;
    }

    public function forUser(int|string $userId): Profile
    {
        $profile = $this->profiles->forUser($userId);
        $this->authorizer->view($profile);

        return $profile;
    }
}
