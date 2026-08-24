<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Livewire\Components;

use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\SocialGraph\Actions\CreateRelationship;
use Livewire\Component;

final class FollowProfile extends Component
{
    public string $profileId = '';

    public function follow(GetProfile $get, CreateRelationship $create): void
    {
        $this->validate(['profileId' => ['required', 'uuid']]);
        $create->follow($get->forUser($this->userId()), $get->byId($this->profileId));
        $this->dispatch('social-graph-followed');
    }

    public function render(): mixed
    {
        return view('social-network-social-graph-livewire::livewire.follow-profile');
    }

    private function userId(): int|string
    {
        abort_unless(auth()->check(), 401);

        return auth()->id();
    }
}
