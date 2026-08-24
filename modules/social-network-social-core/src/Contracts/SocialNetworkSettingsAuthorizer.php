<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialCore\Contracts;

interface SocialNetworkSettingsAuthorizer
{
    public function authorize(int|string $teamId): void;
}
