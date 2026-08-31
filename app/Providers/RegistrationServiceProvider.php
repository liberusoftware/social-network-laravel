<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

final class RegistrationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
    }
}
