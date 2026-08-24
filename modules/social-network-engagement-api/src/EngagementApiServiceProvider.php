<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Engagement\Api;

use Illuminate\Support\ServiceProvider;

final class EngagementApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }
}
