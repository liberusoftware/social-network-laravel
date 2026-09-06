<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Analytics\Authorization;

use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Analytics\Contracts\AnalyticsAuthorizer;

final class GateAnalyticsAuthorizer implements AnalyticsAuthorizer
{
    public function view(object $actor, string $metric): void
    {
        Gate::forUser($actor)->authorize('social-network.analytics.view', $metric);
    }

    public function record(object $actor, string $metric): void
    {
        Gate::forUser($actor)->authorize('social-network.analytics.record', $metric);
    }
}
