<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Moderation\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class ModerationLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'social-network-moderation-livewire');
        Livewire::component('module-social-network-moderation::report-form', Components\ReportForm::class);
    }
}
