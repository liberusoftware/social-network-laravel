<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Livewire\Components;

use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\SocialGraph\Actions\BlockProfile as BlockProfileAction;
use Liberu\SocialNetwork\SocialGraph\Actions\UnblockProfile;
use Livewire\Component;

final class BlockProfile extends Component
{
    public string $profileId = '';

    public function block(GetProfile $get, BlockProfileAction $block): void
    {
        $this->validate(['profileId' => ['required', 'uuid']]);
        $block->handle($get->forUser($this->userId()), $get->byId($this->profileId));
        $this->dispatch('social-graph-profile-blocked');
    }

    public function unblock(GetProfile $get, UnblockProfile $unblock): void
    {
        $this->validate(['profileId' => ['required', 'uuid']]);
        $unblock->handle($get->forUser($this->userId()), $get->byId($this->profileId));
        $this->dispatch('social-graph-profile-unblocked');
    }

    public function render(): mixed
    {
        return view('social-network-social-graph-livewire::livewire.block-profile');
    }

    private function userId(): int|string
    {
        abort_unless(auth()->check(), 401);

        return auth()->id();
    }
}
