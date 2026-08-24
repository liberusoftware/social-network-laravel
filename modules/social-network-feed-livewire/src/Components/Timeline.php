<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Feed\Livewire\Components;

use Liberu\SocialNetwork\Feed\Actions\GetFeed;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Livewire\Component;

final class Timeline extends Component
{
    public function entries(GetProfile $get, GetFeed $feed): mixed
    {
        return $feed->handle($get->forUser($this->userId()));
    }

    public function render(): mixed
    {
        return view('social-network-feed-livewire::livewire.timeline');
    }

    private function userId(): int|string
    {
        abort_unless(auth()->check(), 401);

        return auth()->id();
    }
}
