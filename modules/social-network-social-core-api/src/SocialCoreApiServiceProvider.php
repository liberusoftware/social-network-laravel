<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialCore\Api;

use Illuminate\Support\ServiceProvider;

final class SocialCoreApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }
}
