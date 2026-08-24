<?php
namespace Liberu\SocialNetwork\Federation\Livewire;
use Illuminate\Support\ServiceProvider;
class FederationLivewireServiceProvider extends ServiceProvider { public function boot(): void { $this->loadViewsFrom(__DIR__.'/../resources/views','social-network-federation'); } }
