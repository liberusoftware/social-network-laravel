<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\SocialGraph\Contracts\GraphAuthorizer;
use Liberu\SocialNetwork\SocialGraph\Models\Relationship;

final readonly class UpdateRelationshipVisibility
{
    public function __construct(private GraphAuthorizer $authorizer) {}

    public function handle(Profile $actor, Relationship $relationship, string $visibility): Relationship
    {
        $this->authorizer->visibility($actor, $relationship);

        if (! in_array($visibility, (array) config('social-network-social-graph.visibilities'), true)) {
            throw new InvalidArgumentException('The selected relationship visibility is not supported.');
        }

        DB::transaction(fn () => $relationship->update(['visibility' => $visibility]));

        return $relationship->refresh();
    }
}
