<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Analytics\Livewire\Components;

use Livewire\Component;
use Liberu\SocialNetwork\Analytics\Actions\GetMetrics;

final class Dashboard extends Component
{
    public string $metric = 'growth';

    public int $limit = 30;

    public function snapshots(GetMetrics $metrics): mixed
    {
        abort_unless(auth()->check(), 401);

        return $metrics->handle(auth()->user(), $this->metric, $this->limit);
    }

    public function render(): mixed
    {
        return view('social-network-analytics-livewire::livewire.dashboard');
    }
}
