<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\SocialNetwork\Events\Contracts\EventsAuthorizer;
use Liberu\SocialNetwork\Events\Models\Event;
use Liberu\SocialNetwork\Events\Models\Invitation;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class InviteProfile
{
    public function __construct(private EventsAuthorizer $authorizer) {}

    public function handle(Profile $owner, Event $event, Profile $invitee): Invitation
    {
        $this->authorizer->manage($owner, $event);
        if ((string) $event->owner_profile_id === (string) $invitee->getKey()) {
            throw new InvalidArgumentException('An event owner cannot be invited.');
        }

        return DB::transaction(fn (): Invitation => Invitation::query()->updateOrCreate(
            ['event_id' => $event->getKey(), 'profile_id' => $invitee->getKey()],
            ['state' => 'pending'],
        ));
    }
}
