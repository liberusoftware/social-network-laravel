<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Engagement\Actions;

use Liberu\SocialNetwork\Engagement\Contracts\EngagementAuthorizer;
use Liberu\SocialNetwork\Engagement\Models\Engagement;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class DeleteEngagement
{
    public function __construct(private EngagementAuthorizer $authorizer) {}

    public function handle(Profile $actor, Engagement $engagement): void
    {
        $this->authorizer->create($actor);
        abort_unless((string) $engagement->actor_profile_id === (string) $actor->getKey(), 403);
        $engagement->delete();
    }
}
