<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Events\Api;

use Illuminate\Support\ServiceProvider;

final class EventsApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }
}
