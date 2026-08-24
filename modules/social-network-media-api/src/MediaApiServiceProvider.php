<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Api;

use Illuminate\Support\ServiceProvider;

final class MediaApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }
}
