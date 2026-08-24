<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing\Api;

use Illuminate\Support\ServiceProvider;

final class PublishingApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }
}
