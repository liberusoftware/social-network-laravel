<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\SocialGraph\Contracts\GraphAuthorizer;
use Liberu\SocialNetwork\SocialGraph\Models\Block;
use Liberu\SocialNetwork\SocialGraph\Models\Relationship;

final readonly class BlockProfile
{
    public function __construct(private GraphAuthorizer $authorizer) {}

    public function handle(Profile $source, Profile $target): Block
    {
        if ($source->is($target)) {
            throw new InvalidArgumentException('A profile cannot block itself.');
        }

        $this->authorizer->block($source, $target);

        return DB::transaction(function () use ($source, $target): Block {
            Relationship::query()
                ->where(function ($query) use ($source, $target): void {
                    $query->where('source_profile_id', $source->getKey())->where('target_profile_id', $target->getKey());
                })->orWhere(function ($query) use ($source, $target): void {
                    $query->where('source_profile_id', $target->getKey())->where('target_profile_id', $source->getKey());
                })->delete();

            return Block::query()->firstOrCreate(
                ['source_profile_id' => $source->getKey(), 'target_profile_id' => $target->getKey()],
                ['id' => (string) Str::uuid()],
            );
        });
    }
}
