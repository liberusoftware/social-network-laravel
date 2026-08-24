<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Profiles\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class ProfilesLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-profiles-livewire');
        Livewire::component('module-social-network-profiles::editor', Components\ProfileEditor::class);
    }
}
