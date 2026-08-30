<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Moderation\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\Moderation\Models\ModerationReport;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class Reports extends Page
{
    protected string $view = 'social-network-moderation-filament::pages.reports';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-exclamation';

    protected static string|\UnitEnum|null $navigationGroup = 'Social Network';

    public function reports(): mixed
    {
        abort_unless(auth()->check() && Gate::forUser(auth()->user())->allows('social-network.moderation.decide', [new Profile(['user_id' => auth()->id()])]), 403);

        return ModerationReport::query()->latest()->limit(50)->get();
    }
}
