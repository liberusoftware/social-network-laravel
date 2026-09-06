<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Analytics\Contracts;

interface AnalyticsAuthorizer
{
    public function view(object $actor, string $metric): void;

    public function record(object $actor, string $metric): void;
}
