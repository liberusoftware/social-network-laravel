<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Profiles\Livewire\Components;

use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\Profiles\Actions\UpdateProfile;
use Livewire\Component;

final class ProfileEditor extends Component
{
    public string $handle = '';

    public string $bio = '';

    public string $visibility = 'public';

    public string $avatarPath = '';

    public function mount(GetProfile $get): void
    {
        $profile = $get->forUser($this->userId());
        $this->handle = $profile->handle;
        $this->bio = (string) $profile->bio;
        $this->visibility = $profile->visibility;
        $this->avatarPath = (string) $profile->avatar_path;
    }

    public function save(GetProfile $get, UpdateProfile $update): void
    {
        $this->validate([
            'handle' => ['required', 'min:3', 'max:32', 'regex:/^[A-Za-z0-9_]+$/'],
            'bio' => ['nullable', 'max:5000'],
            'visibility' => ['required', 'in:public,followers,private'],
            'avatarPath' => ['nullable', 'max:2048'],
        ]);
        $update->handle($get->forUser($this->userId()), [
            'handle' => $this->handle,
            'bio' => $this->bio,
            'visibility' => $this->visibility,
            'avatar_path' => $this->avatarPath,
        ]);
        $this->dispatch('profile-saved');
    }

    public function render(): mixed
    {
        return view('social-network-profiles-livewire::livewire.profile-editor');
    }

    private function userId(): int|string
    {
        abort_unless(auth()->check(), 401);

        return auth()->id();
    }
}
