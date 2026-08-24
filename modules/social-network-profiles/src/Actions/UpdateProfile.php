<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Profiles\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Liberu\SocialNetwork\Profiles\Contracts\ProfileAuthorizer;
use Liberu\SocialNetwork\Profiles\Contracts\ProfileRepository;
use Liberu\SocialNetwork\Profiles\Events\ProfileUpdated;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class UpdateProfile
{
    public function __construct(private ProfileRepository $profiles, private ProfileAuthorizer $authorizer, private Dispatcher $events) {}

    /** @param array<string, mixed> $attributes */
    public function handle(Profile $profile, array $attributes): Profile
    {
        $this->authorizer->update($profile);
        $this->validate($attributes);
        $updated = DB::transaction(function () use ($profile, $attributes): Profile {
            $profile->fill(Arr::only($attributes, ['handle', 'bio', 'attributes', 'avatar_path', 'visibility', 'lifecycle_state']));
            $profile->handle = Str::lower(trim((string) $profile->handle));
            $profile->save();

            return $profile->refresh();
        });
        $this->events->dispatch(new ProfileUpdated($updated));

        return $updated;
    }

    /** @param array<string, mixed> $attributes */
    private function validate(array $attributes): void
    {
        if (isset($attributes['handle']) && ! preg_match('/^[a-z0-9_]{3,32}$/i', (string) $attributes['handle'])) {
            throw new InvalidArgumentException('Handles must contain only letters, numbers, and underscores.');
        }
        if (isset($attributes['visibility']) && ! in_array($attributes['visibility'], (array) config('social-network-profiles.visibilities'), true)) {
            throw new InvalidArgumentException('The selected profile visibility is not supported.');
        }
        if (isset($attributes['lifecycle_state']) && ! in_array($attributes['lifecycle_state'], (array) config('social-network-profiles.lifecycle_states'), true)) {
            throw new InvalidArgumentException('The selected profile lifecycle state is not supported.');
        }
    }
}
