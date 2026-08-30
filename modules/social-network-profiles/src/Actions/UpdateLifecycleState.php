<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Profiles\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use InvalidArgumentException;
use Liberu\SocialNetwork\Profiles\Events\ProfileLifecycleUpdated;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class UpdateLifecycleState
{
    public function __construct(private Dispatcher $events) {}

    public function handle(Profile $profile, string $state): Profile
    {
        Gate::authorize('social-network.profiles.lifecycle', [$profile]);
        if (! in_array($state, (array) config('social-network-profiles.lifecycle_states'), true)) {
            throw new InvalidArgumentException('The selected profile lifecycle state is not supported.');
        }
        $updated = DB::transaction(function () use ($profile, $state): Profile {
            $profile->forceFill(['lifecycle_state' => $state])->save();
            if ($state === 'deleted' && ! $profile->trashed()) {
                $profile->delete();

                return $profile;
            }
            if ($state !== 'deleted' && $profile->trashed()) {
                $profile->restore();
            }

            return $profile->refresh();
        });
        $this->events->dispatch(new ProfileLifecycleUpdated($updated, $state));

        return $updated;
    }
}
