<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Profiles\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use InvalidArgumentException;
use Liberu\SocialNetwork\Profiles\Events\ProfileVerificationUpdated;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class UpdateVerificationStatus
{
    public function __construct(private Dispatcher $events) {}

    public function handle(Profile $profile, string $status): Profile
    {
        Gate::authorize('social-network.profiles.verify', [$profile]);
        if (! in_array($status, (array) config('social-network-profiles.verification_statuses'), true)) {
            throw new InvalidArgumentException('The selected verification status is not supported.');
        }
        $updated = DB::transaction(function () use ($profile, $status): Profile {
            $profile->forceFill(['verification_status' => $status])->save();

            return $profile->refresh();
        });
        $this->events->dispatch(new ProfileVerificationUpdated($updated, $status));

        return $updated;
    }
}
