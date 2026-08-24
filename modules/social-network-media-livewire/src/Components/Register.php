<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Livewire\Components;

use Liberu\SocialNetwork\Media\Actions\RegisterMediaAsset;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Livewire\Component;

final class Register extends Component
{
    public string $type = 'image';

    public string $path = '';

    public string $altText = '';

    public function save(GetProfile $get, RegisterMediaAsset $register): void
    {
        $this->validate(['type' => ['required', 'in:image,video,audio,file'], 'path' => ['required', 'max:2048'], 'altText' => ['nullable', 'max:1000']]);
        $register->handle($get->forUser($this->userId()), ['type' => $this->type, 'path' => $this->path, 'alt_text' => $this->altText]);
        $this->reset('path', 'altText');
        $this->dispatch('media-registered');
    }

    public function render(): mixed
    {
        return view('social-network-media-livewire::livewire.register');
    }

    private function userId(): int|string
    {
        abort_unless(auth()->check(), 401);

        return auth()->id();
    }
}
