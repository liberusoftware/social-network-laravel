<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Discovery\Livewire\Components;

use Liberu\SocialNetwork\Discovery\Actions\SearchDiscovery;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Livewire\Component;

final class Search extends Component
{
    public string $query = '';

    public array $results = [];

    public function search(GetProfile $get, SearchDiscovery $search): void
    {
        abort_unless(auth()->check(), 401);
        $this->validate(['query' => ['required', 'string', 'max:10000']]);
        $this->results = $search->handle($get->forUser((string) auth()->id()), $this->query)->map(fn ($item) => ['id' => $item->getKey(), 'body' => $item->body, 'resource_type' => $item->resource_type])->all();
    }

    public function render(): mixed
    {
        return view('social-network-discovery-livewire::livewire.search');
    }
}
