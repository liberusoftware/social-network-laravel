<?php
namespace Liberu\SocialNetwork\Federation\Api;
use Illuminate\Support\ServiceProvider;
class FederationApiServiceProvider extends ServiceProvider { public function boot(): void { $this->loadRoutesFrom(__DIR__.'/../routes/api.php'); } }
