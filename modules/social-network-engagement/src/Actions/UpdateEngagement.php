<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Engagement\Actions;

use Illuminate\Support\Facades\Gate;
use InvalidArgumentException;
use Liberu\SocialNetwork\Engagement\Models\Engagement;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class UpdateEngagement
{
    public function handle(Profile $actor, Engagement $engagement, string $body): Engagement
    {
        Gate::authorize('social-network.engagement.update', [$actor, $engagement]);
        abort_unless((string) $engagement->actor_profile_id === (string) $actor->getKey(), 403);

        $body = trim($body);
        if (! in_array($engagement->kind, ['comment', 'reply'], true) || $body === '') {
            throw new InvalidArgumentException('Only non-empty comments and replies can be updated.');
        }
        if (mb_strlen($body) > (int) config('social-network-engagement.comment_max_length')) {
            throw new InvalidArgumentException('Comment text exceeds the configured limit.');
        }

        $engagement->update(['body' => $body]);

        return $engagement->refresh();
    }
}
