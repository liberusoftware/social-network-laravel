<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\SocialGraph\Contracts\GraphAuthorizer;
use Liberu\SocialNetwork\SocialGraph\Models\GraphList;

final readonly class CreateList
{
    public function __construct(private GraphAuthorizer $authorizer) {}

    /** @param array{name?: string, visibility?: string} $attributes */
    public function handle(Profile $owner, array $attributes): GraphList
    {
        $this->authorizer->list($owner);
        $name = trim((string) ($attributes['name'] ?? ''));
        $visibility = (string) ($attributes['visibility'] ?? 'private');
        if ($name === '' || mb_strlen($name) > 80) {
            throw new InvalidArgumentException('List names must contain between 1 and 80 characters.');
        }
        if (! in_array($visibility, (array) config('social-network-social-graph.visibilities'), true)) {
            throw new InvalidArgumentException('The selected list visibility is not supported.');
        }

        return DB::transaction(fn (): GraphList => GraphList::query()->create([
            'id' => (string) Str::uuid(), 'owner_profile_id' => $owner->getKey(), 'name' => $name, 'visibility' => $visibility,
        ]));
    }
}
