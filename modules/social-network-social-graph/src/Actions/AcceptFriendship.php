<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Actions;

use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\SocialGraph\Models\Relationship;

final class AcceptFriendship
{
    public function handle(Profile $actor, Relationship $relationship): Relationship
    {
        Gate::authorize('social-network.social-graph.friend', [$actor, $relationship]);
        abort_unless($relationship->target_profile_id === $actor->getKey(), 403);
        abort_unless($relationship->relationship_type === 'friend' && $relationship->status === 'pending', 422);
        $relationship->update(['status' => 'accepted']);

        return $relationship->refresh();
    }
}
