<?php
namespace Liberu\SocialNetwork\Federation;
use Illuminate\Support\ServiceProvider;
class FederationServiceProvider extends ServiceProvider { public function boot(): void { $this->publishes([__DIR__.'/../config/social-network-federation.php'=>config_path('social-network-federation.php')],'social-network-federation-config'); $this->loadMigrationsFrom(__DIR__.'/../database/migrations'); } public function register(): void { $this->mergeConfigFrom(__DIR__.'/../config/social-network-federation.php','social-network-federation'); } }
