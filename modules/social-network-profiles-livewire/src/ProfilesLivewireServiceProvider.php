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
        Livewire::component('module-social-network-profiles::handles', Components\ProfileEditor::class);
        Livewire::component('module-social-network-profiles::bios', Components\ProfileEditor::class);
        Livewire::component('module-social-network-profiles::attributes', Components\ProfileEditor::class);
        Livewire::component('module-social-network-profiles::avatars', Components\ProfileEditor::class);
        Livewire::component('module-social-network-profiles::verification', Components\ProfileActions::class);
        Livewire::component('module-social-network-profiles::visibility', Components\ProfileEditor::class);
        Livewire::component('module-social-network-profiles::blocking', Components\ProfileActions::class);
        Livewire::component('module-social-network-profiles::profile-lifecycle', Components\ProfileActions::class);
    }
}
