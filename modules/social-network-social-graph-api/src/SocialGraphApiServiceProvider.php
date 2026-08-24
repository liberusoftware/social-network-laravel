<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Api;

use Illuminate\Support\ServiceProvider;

final class SocialGraphApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }
}
