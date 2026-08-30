<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Actions;

use Illuminate\Database\Eloquent\Collection;
use Liberu\SocialNetwork\Events\Models\Event;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class ListEvents
{
    public function handle(Profile $viewer, int $limit = 25): Collection
    {
        $limit = max(1, min($limit, 100));

        return Event::query()
            ->where(function ($query) use ($viewer): void {
                $query->where(function ($published) use ($viewer): void {
                    $published->whereIn('state', ['published', 'completed'])
                        ->where(function ($visible) use ($viewer): void {
                            $visible->where('visibility', 'public')
                                ->orWhere('owner_profile_id', $viewer->getKey())
                                ->orWhereExists(function ($invited) use ($viewer): void {
                                    $invited->selectRaw('1')
                                        ->from('social_event_invitations')
                                        ->whereColumn('social_event_invitations.event_id', 'social_events.id')
                                        ->where('social_event_invitations.profile_id', $viewer->getKey())
                                        ->whereIn('social_event_invitations.state', ['pending', 'accepted']);
                                });
                        });
                })->orWhere('owner_profile_id', $viewer->getKey());
            })
            ->orderBy('starts_at')
            ->limit($limit)
            ->get();
    }
}
