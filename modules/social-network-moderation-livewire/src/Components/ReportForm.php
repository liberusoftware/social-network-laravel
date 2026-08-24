<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Moderation\Livewire\Components;

use Liberu\SocialNetwork\Moderation\Actions\CreateReport;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Livewire\Component;

final class ReportForm extends Component
{
    public string $targetId = '';

    public string $reason = '';

    public string $details = '';

    public function submit(GetProfile $get, CreateReport $create): void
    {
        $this->validate(['targetId' => ['required', 'uuid'], 'reason' => ['required', 'max:120'], 'details' => ['nullable', 'max:10000']]);
        $create->handle($get->forUser($this->userId()), 'content', $this->targetId, $this->reason, $this->details);
        $this->reset();
        $this->dispatch('report-created');
    }

    public function render(): mixed
    {
        return view('social-network-moderation-livewire::livewire.report-form');
    }

    private function userId(): int|string
    {
        abort_unless(auth()->check(), 401);

        return auth()->id();
    }
}
