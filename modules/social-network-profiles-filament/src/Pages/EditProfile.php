<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Profiles\Filament\Pages;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\Profiles\Actions\UpdateProfile as UpdateProfileAction;

final class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'social-network-profiles-filament::pages.edit-profile';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static string|\UnitEnum|null $navigationGroup = 'Social Network';

    protected static ?string $navigationLabel = 'Profile';

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(GetProfile $get): void
    {
        $userId = auth()->id();
        abort_unless($userId !== null, 404);
        $this->data = $get->forUser($userId)->only(['handle', 'bio', 'avatar_path', 'visibility']);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->components([
            Section::make('Profile')->schema([
                TextInput::make('handle')->required()->minLength(3)->maxLength(32),
                Textarea::make('bio')->maxLength(5000),
                TextInput::make('avatar_path')->maxLength(2048),
                Select::make('visibility')->options(['public' => 'Public', 'followers' => 'Followers', 'private' => 'Private'])->required(),
            ]),
        ]);
    }

    public function save(GetProfile $get, UpdateProfileAction $update): void
    {
        $userId = auth()->id();
        abort_unless($userId !== null, 404);
        $update->handle($get->forUser($userId), $this->data);
        $this->redirect(request()->header('Referer') ?: url()->current());
    }
}
