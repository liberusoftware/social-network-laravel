<?php

namespace App\Filament\App\Pages;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Liberu\Foundation\Organizations\Models\Team;

final class SetupAccount extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.app.pages.setup-account';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    protected static string|\UnitEnum|null $navigationGroup = 'Account';

    protected static ?string $navigationLabel = 'Get started';

    protected static ?int $navigationSort = 1;

    /** @var array<string, string> */
    public array $data = [];

    /** @var list<string> */
    public array $providers = [];

    /** @var list<string> */
    public array $connectedProviders = [];

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->current_team_id !== null;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return self::canAccess() && auth()->user()->onboarding_completed_at === null;
    }

    public function mount(): void
    {
        $user = auth()->user();
        $team = Team::query()->findOrFail($user->current_team_id);

        $this->data = [
            'name' => $user->name,
            'team_name' => (string) $team->getAttribute('name'),
            'timezone' => $user->timezone ?? config('app.timezone', 'UTC'),
        ];
        $this->providers = array_values(array_map('strval', (array) config('socialstream.providers', [])));
        $this->connectedProviders = $user->connectedAccounts()->pluck('provider')->map(fn ($provider): string => (string) $provider)->all();
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->components([
            Wizard::make([
                Step::make('Your details')
                    ->description('Make your profile feel like you.')
                    ->schema([
                        TextInput::make('name')->label('Display name')->required()->maxLength(255),
                        TextInput::make('timezone')->label('Timezone')->required()->maxLength(64)->helperText('Use an IANA timezone such as Europe/London or America/New_York.'),
                    ]),
                Step::make('Your team')
                    ->description('Give your workspace a clear identity.')
                    ->schema([
                        TextInput::make('team_name')->label('Team name')->required()->maxLength(255)->helperText('You can invite teammates and change this later.'),
                    ]),
                Step::make('Connections')
                    ->description('Connect optional services when you are ready.')
                    ->schema([
                        Section::make('OAuth and API access')
                            ->description('OAuth connections are linked to your account. Application client IDs and secrets stay in the server environment; never paste those secrets into a team setting.')
                            ->schema([
                                Placeholder::make('connection_hint')->content('You can connect a provider below or continue and do it later from Account.'),
                            ]),
                    ]),
            ])->columnSpanFull(),
        ]);
    }

    public function completeSetup(): void
    {
        $this->validate();
        $user = auth()->user();

        DB::transaction(function () use ($user): void {
            $team = Team::query()->findOrFail($user->current_team_id);
            abort_unless($user->ownsTeam($team), 403);

            $team->update(['name' => $this->data['team_name']]);
            $user->forceFill([
                'name' => $this->data['name'],
                'timezone' => $this->data['timezone'],
                'onboarding_completed_at' => now(),
            ])->save();
        });

        Notification::make()->title('Account setup complete')->success()->send();
        $this->redirect(route('filament.app.pages.dashboard'));
    }

    public function providerLabel(string $provider): string
    {
        return Str::headline(str_replace('-', ' ', $provider));
    }
}
