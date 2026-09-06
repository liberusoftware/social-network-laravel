<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Livewire\Components;

use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\SocialGraph\Actions\SuggestProfiles;
use Livewire\Component;

final class Suggestions extends Component
{
    /** @var array<int, array{id: string, handle: string}> */
    public array $profiles = [];

    public function mount(GetProfile $get, SuggestProfiles $suggest): void
    {
        $this->profiles = $suggest->for($get->forUser($this->userId()))
            ->map(fn ($profile): array => ['id' => (string) $profile->getKey(), 'handle' => (string) $profile->handle])
            ->all();
    }

    public function render(): mixed
    {
        return view('social-network-social-graph-livewire::livewire.suggestions');
    }

    private function userId(): int|string
    {
        abort_unless(auth()->check(), 401);

        return auth()->id();
    }
}
