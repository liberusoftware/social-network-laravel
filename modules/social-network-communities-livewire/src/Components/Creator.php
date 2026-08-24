<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Communities\Livewire\Components;

use Liberu\SocialNetwork\Communities\Actions\CreateCommunity;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Livewire\Component;

final class Creator extends Component
{
    public string $name = '';

    public string $description = '';

    public function save(GetProfile $get, CreateCommunity $create): void
    {
        $this->validate(['name' => ['required', 'max:120'], 'description' => ['nullable', 'max:10000']]);
        $create->handle($get->forUser($this->userId()), ['name' => $this->name, 'description' => $this->description]);
        $this->reset();
        $this->dispatch('community-created');
    }

    public function render(): mixed
    {
        return view('social-network-communities-livewire::livewire.creator');
    }

    private function userId(): int|string
    {
        abort_unless(auth()->check(), 401);

        return auth()->id();
    }
}
