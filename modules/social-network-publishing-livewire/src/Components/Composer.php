<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing\Livewire\Components;

use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\Publishing\Actions\CreatePublication;
use Livewire\Component;

final class Composer extends Component
{
    public string $title = '';

    public string $body = '';

    public function save(GetProfile $get, CreatePublication $create): void
    {
        $this->validate(['title' => ['nullable', 'max:240'], 'body' => ['required_without:title', 'nullable', 'max:100000']]);
        $create->handle($get->forUser($this->userId()), ['title' => $this->title, 'body' => $this->body]);
        $this->reset();
        $this->dispatch('publication-saved');
    }

    public function render(): mixed
    {
        return view('social-network-publishing-livewire::livewire.composer');
    }

    private function userId(): int|string
    {
        abort_unless(auth()->check(), 401);

        return auth()->id();
    }
}
