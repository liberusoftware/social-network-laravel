<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Notifications\Livewire\Components;

use Liberu\SocialNetwork\Notifications\Actions\MarkRead;
use Liberu\SocialNetwork\Notifications\Models\SocialNotification;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Livewire\Component;

final class ListNotifications extends Component
{
    public function notifications(GetProfile $get): mixed
    {
        return SocialNotification::query()
            ->where('profile_id', $get->forUser($this->userId())->getKey())
            ->latest()
            ->limit(50)
            ->get();
    }

    public function read(string $id, GetProfile $get, MarkRead $mark): void
    {
        $mark->handle($get->forUser($this->userId()), $id);
        $this->dispatch('notification-read');
    }

    public function refreshNotifications(): void {}

    public function getListeners(): array
    {
        abort_unless(auth()->check(), 401);
        $profile = app(GetProfile::class)->forUser(auth()->id());

        return [
            'echo-private:social-notifications.'.$profile->getKey().',notification.created' => 'refreshNotifications',
            'echo-private:social-notifications.'.$profile->getKey().',notification.state-changed' => 'refreshNotifications',
        ];
    }

    public function render(): mixed
    {
        return view('social-network-notifications-livewire::livewire.list-notifications');
    }

    private function userId(): int|string
    {
        abort_unless(auth()->check(), 401);

        return auth()->id();
    }
}
