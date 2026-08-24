<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Livewire\Components;

use Liberu\SocialNetwork\Messaging\Actions\SendMessage;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Livewire\Component;

final class Composer extends Component
{
    public string $conversationId = '';

    public string $body = '';

    public function send(GetProfile $get, SendMessage $send): void
    {
        $this->validate(['conversationId' => ['required', 'uuid'], 'body' => ['required', 'max:10000']]);
        $send->handle($get->forUser($this->userId()), $this->conversationId, $this->body);
        $this->reset('body');
        $this->dispatch('message-sent');
    }

    public function render(): mixed
    {
        return view('social-network-messaging-livewire::livewire.composer');
    }

    private function userId(): int|string
    {
        abort_unless(auth()->check(), 401);

        return auth()->id();
    }
}
