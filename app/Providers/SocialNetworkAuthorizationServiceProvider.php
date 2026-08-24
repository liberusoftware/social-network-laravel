<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Liberu\Foundation\Organizations\Models\Team;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class SocialNetworkAuthorizationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::before(function (?object $user, string $ability, array $arguments): ?bool {
            if (! str_starts_with($ability, 'social-network.')) {
                return null;
            }

            if ($user === null) {
                return false;
            }

            if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
                return true;
            }

            $subject = $arguments[0] ?? null;

            if ($subject instanceof Profile) {
                if ($ability === 'social-network.profiles.view' && $subject->getAttribute('visibility') === 'public') {
                    return true;
                }

                return (string) $subject->getAttribute('user_id') === (string) $user->getAuthIdentifier();
            }

            if (is_int($subject) || is_string($subject)) {
                $team = Team::query()->find($subject);

                return $team !== null && $user->belongsToTeam($team);
            }

            return false;
        });
    }
}
