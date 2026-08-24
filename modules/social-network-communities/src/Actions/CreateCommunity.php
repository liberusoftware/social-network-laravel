<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Communities\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Liberu\SocialNetwork\Communities\Contracts\CommunityAuthorizer;
use Liberu\SocialNetwork\Communities\Events\CommunityCreated;
use Liberu\SocialNetwork\Communities\Models\Community;
use Liberu\SocialNetwork\Communities\Models\Membership;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class CreateCommunity
{
    public function __construct(private CommunityAuthorizer $authorizer, private Dispatcher $events) {}

    /** @param array<string,mixed> $attributes */
    public function handle(Profile $owner, array $attributes): Community
    {
        $this->authorizer->create($owner);
        $name = trim((string) ($attributes['name'] ?? ''));
        if ($name === '') {
            throw new InvalidArgumentException('Community names are required.');
        } $community = DB::transaction(function () use ($owner, $attributes, $name): Community {
            $community = Community::query()->create(['id' => (string) Str::uuid(), 'owner_profile_id' => $owner->getKey(), 'name' => $name, 'slug' => Str::slug($name).'-'.Str::lower(Str::random(6)), 'description' => $attributes['description'] ?? null, 'visibility' => $attributes['visibility'] ?? 'public', 'rules' => $attributes['rules'] ?? []]);
            Membership::query()->create(['community_id' => $community->getKey(), 'profile_id' => $owner->getKey(), 'role' => 'owner', 'status' => 'active']);

            return $community;
        });
        $this->events->dispatch(new CommunityCreated($community));

        return $community;
    }
}
