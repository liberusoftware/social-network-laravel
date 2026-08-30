<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Discovery\Authorization;

use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Discovery\Contracts\DiscoveryAuthorizer;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class GateDiscoveryAuthorizer implements DiscoveryAuthorizer
{
    public function search(Profile $viewer): void { Gate::authorize('social-network.discovery.search', [$viewer]); }
    public function index(Profile $owner): void { Gate::authorize('social-network.discovery.index', [$owner]); }
}
