<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Engagement\Livewire\Components;

use Liberu\SocialNetwork\Engagement\Actions\CreateEngagement;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Livewire\Component;

final class Reactor extends Component
{
    public string $targetId = '';

    public string $reactionType = 'like';

    public function react(GetProfile $get, CreateEngagement $create): void
    {
        $this->validate(['targetId' => ['required', 'uuid'], 'reactionType' => ['required', 'in:like,love,celebrate,insightful']]);
        $create->handle($get->forUser($this->userId()), ['kind' => 'reaction', 'target_type' => 'publication', 'target_id' => $this->targetId, 'reaction_type' => $this->reactionType]);
        $this->dispatch('engagement-created');
    }

    public function render(): mixed
    {
        return view('social-network-engagement-livewire::livewire.reactor');
    }

    private function userId(): int|string
    {
        abort_unless(auth()->check(), 401);

        return auth()->id();
    }
}
