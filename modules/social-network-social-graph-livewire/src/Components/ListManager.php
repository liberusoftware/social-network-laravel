<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Livewire\Components;

use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\SocialGraph\Actions\CreateList;
use Liberu\SocialNetwork\SocialGraph\Actions\ListOwnedLists;
use Livewire\Component;

final class ListManager extends Component
{
    public string $name = '';

    public string $visibility = 'private';

    /** @var array<int, array{id: string, name: string, visibility: string}> */
    public array $ownedLists = [];

    public function mount(GetProfile $get, ListOwnedLists $lists): void
    {
        $this->refreshLists($get, $lists);
    }

    public function create(GetProfile $get, CreateList $create, ListOwnedLists $lists): void
    {
        $this->validate(['name' => ['required', 'string', 'min:1', 'max:80'], 'visibility' => ['required', 'in:public,followers,private']]);
        $create->handle($get->forUser($this->userId()), ['name' => $this->name, 'visibility' => $this->visibility]);
        $this->reset('name');
        $this->refreshLists($get, $lists);
        $this->dispatch('social-graph-list-created');
    }

    public function render(): mixed
    {
        return view('social-network-social-graph-livewire::livewire.lists');
    }

    private function userId(): int|string
    {
        abort_unless(auth()->check(), 401);

        return auth()->id();
    }

    private function refreshLists(GetProfile $get, ListOwnedLists $lists): void
    {
        $this->ownedLists = $lists->handle($get->forUser($this->userId()))
            ->map(fn ($list): array => ['id' => (string) $list->getKey(), 'name' => (string) $list->name, 'visibility' => (string) $list->visibility])->all();
    }
}
