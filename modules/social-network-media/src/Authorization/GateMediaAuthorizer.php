<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Authorization;

use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Media\Contracts\MediaAuthorizer;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class GateMediaAuthorizer implements MediaAuthorizer
{
    public function upload(Profile $owner): void
    {
        Gate::authorize('social-network.media.upload', [$owner]);
    }

    public function update(Profile $owner): void
    {
        Gate::authorize('social-network.media.update', [$owner]);
    }
}
