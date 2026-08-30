<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Profiles\Livewire\Components;

use Illuminate\Validation\Rule;
use Liberu\SocialNetwork\Profiles\Actions\BlockProfile;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\Profiles\Actions\UnblockProfile;
use Liberu\SocialNetwork\Profiles\Actions\UpdateLifecycleState;
use Liberu\SocialNetwork\Profiles\Actions\UpdateVerificationStatus;
use Livewire\Component;

final class ProfileActions extends Component
{
    public string $profileId = '';

    public string $lifecycleState = 'active';

    public string $verificationStatus = 'pending';

    public function block(GetProfile $get, BlockProfile $block): void
    {
        $block->handle($get->forUser((string) auth()->id()), $get->byId($this->profileId));
    }

    public function unblock(GetProfile $get, UnblockProfile $unblock): void
    {
        $unblock->handle($get->forUser((string) auth()->id()), $get->byId($this->profileId));
    }

    public function updateLifecycle(GetProfile $get, UpdateLifecycleState $update): void
    {
        $this->validate(['lifecycleState' => ['required', Rule::in((array) config('social-network-profiles.lifecycle_states'))]]);
        $update->handle($get->forUser((string) auth()->id()), $this->lifecycleState);
    }

    public function updateVerification(GetProfile $get, UpdateVerificationStatus $update): void
    {
        $this->validate(['verificationStatus' => ['required', Rule::in((array) config('social-network-profiles.verification_statuses'))]]);
        $update->handle($get->byId($this->profileId), $this->verificationStatus);
    }

    public function render(): mixed
    {
        return view('social-network-profiles-livewire::livewire.profile-actions');
    }
}
