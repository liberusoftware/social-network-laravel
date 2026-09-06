<?php

namespace Liberu\SocialNetwork\Analytics\Api;

use Illuminate\Support\ServiceProvider;

class AnalyticsApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }
}
