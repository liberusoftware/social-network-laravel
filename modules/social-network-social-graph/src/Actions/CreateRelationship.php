<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\SocialGraph\Contracts\GraphAuthorizer;
use Liberu\SocialNetwork\SocialGraph\Events\RelationshipCreated;
use Liberu\SocialNetwork\SocialGraph\Models\Relationship;

final readonly class CreateRelationship
{
    public function __construct(private GraphAuthorizer $authorizer, private Dispatcher $events) {}

    public function follow(Profile $source, Profile $target): Relationship
    {
        return $this->create($source, $target, 'follow', 'accepted');
    }

    public function friend(Profile $source, Profile $target): Relationship
    {
        return $this->create($source, $target, 'friend', 'pending');
    }

    private function create(Profile $source, Profile $target, string $type, string $status): Relationship
    {
        if ($source->is($target)) {
            throw new InvalidArgumentException('A profile cannot relate to itself.');
        } $type === 'follow' ? $this->authorizer->follow($source, $target) : $this->authorizer->friend($source, $target);
        [$relationship, $created] = DB::transaction(function () use ($source, $target, $type, $status): array {
            $relationship = Relationship::query()->firstOrCreate(
                ['source_profile_id' => $source->getKey(), 'target_profile_id' => $target->getKey(), 'relationship_type' => $type],
                ['id' => (string) Str::uuid(), 'status' => $status],
            );

            return [$relationship, $relationship->wasRecentlyCreated];
        });

        if ($created) {
            $this->events->dispatch(new RelationshipCreated($relationship));
        }

        return $relationship;
    }
}
