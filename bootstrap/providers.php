<?php

use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\AppPanelProvider;
use App\Providers\RegistrationServiceProvider;
use App\Providers\SocialNetworkAuthorizationServiceProvider;
use Liberu\Foundation\ModuleManager\ModuleManagerServiceProvider;

return [
    ModuleManagerServiceProvider::class,
    RegistrationServiceProvider::class,
    SocialNetworkAuthorizationServiceProvider::class,
    AdminPanelProvider::class,
    AppPanelProvider::class,
];
