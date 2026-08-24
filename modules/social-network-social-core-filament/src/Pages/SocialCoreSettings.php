<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialCore\Filament\Pages;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\SocialCore\Actions\GetSocialNetworkSettings;
use Liberu\SocialNetwork\SocialCore\Actions\UpdateSocialNetworkSettings;

final class SocialCoreSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'social-network-social-core-filament::pages.social-core-settings';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static string|\UnitEnum|null $navigationGroup = 'Social Network';

    protected static ?string $navigationLabel = 'Social Core';

    /** @var array<string, mixed> */
    public array $data = [];

    public static function canAccess(): bool
    {
        $teamId = auth()->user()?->current_team_id;

        return $teamId !== null && Gate::allows('social-network.social-core.view', [$teamId]);
    }

    public function mount(GetSocialNetworkSettings $get): void
    {
        $teamId = auth()->user()?->current_team_id;
        abort_unless($teamId !== null, 404);
        $this->data = $get->handle($teamId)->only([
            'deployment_mode', 'network_settings', 'terminology', 'feature_policy', 'shared_ids',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->components([
            Section::make('Social Core')->schema([
                Select::make('deployment_mode')->options([
                    'hosted' => 'Hosted', 'self_hosted' => 'Self hosted', 'federated' => 'Federated',
                ])->required(),
                KeyValue::make('network_settings')->label('Network settings'),
                KeyValue::make('terminology')->label('Terminology'),
                KeyValue::make('feature_policy')->label('Feature policy'),
                KeyValue::make('shared_ids')->label('Shared IDs'),
            ])->columns(1),
        ]);
    }

    public function save(UpdateSocialNetworkSettings $update): void
    {
        $teamId = auth()->user()?->current_team_id;
        abort_unless($teamId !== null, 404);
        $update->handle($teamId, $this->data);
        $this->redirect(request()->header('Referer') ?: url()->current());
    }
}
