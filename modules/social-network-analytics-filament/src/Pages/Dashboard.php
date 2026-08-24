<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Analytics\Filament\Pages;

use Filament\Pages\Page;
use Liberu\SocialNetwork\Analytics\Actions\GetMetrics;

final class Dashboard extends Page
{
    protected string $view = 'social-network-analytics-filament::pages.dashboard';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static string|\UnitEnum|null $navigationGroup = 'Social Network';

    public function snapshots(GetMetrics $metrics): mixed
    {
        abort_unless(auth()->check(), 404);
        return $metrics->handle(auth()->user(), 'growth', 30);
    }
}
