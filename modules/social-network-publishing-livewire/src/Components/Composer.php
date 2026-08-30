<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing\Livewire\Components;

use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\Publishing\Actions\CreatePublication;
use Livewire\Component;

class Composer extends Component
{
    public string $title = '';

    public string $body = '';

    public string $kind = 'post';

    public string $audience = 'public';

    public function save(GetProfile $get, CreatePublication $create): void
    {
        $this->validate(['kind' => ['required', 'in:post,article'], 'audience' => ['required', 'in:public,followers,private'], 'title' => ['nullable', 'max:240'], 'body' => ['required_without:title', 'nullable', 'max:100000']]);
        $create->handle($get->forUser($this->userId()), ['kind' => $this->kind, 'audience' => $this->audience, 'title' => $this->title, 'body' => $this->body]);
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
