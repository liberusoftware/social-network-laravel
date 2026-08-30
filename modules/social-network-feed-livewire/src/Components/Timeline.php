<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Feed\Livewire\Components;

use Liberu\SocialNetwork\Feed\Actions\GetFeed;
use Liberu\SocialNetwork\Feed\Actions\UpdateFeedControls;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Livewire\Component;

final class Timeline extends Component
{
    public string $mode = 'ranked';

    public array $filters = [];

    public array $hiddenItems = [];

    public ?string $after = null;

    public function entries(GetProfile $get, GetFeed $feed): mixed
    {
        return $feed->handle($get->forUser($this->userId()), 20, $this->after);
    }

    public function updateControls(UpdateFeedControls $update): void
    {
        $this->validate([
            'mode' => ['required', 'in:ranked,chronological'],
            'filters' => ['array', 'max:20'],
            'hiddenItems' => ['array', 'max:500'],
        ]);
        $update->handle(app(GetProfile::class)->forUser($this->userId()), [
            'mode' => $this->mode,
            'filters' => $this->filters,
            'hidden_items' => $this->hiddenItems,
        ]);
        $this->after = null;
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
