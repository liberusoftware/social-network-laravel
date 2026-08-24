<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialGraph\Livewire\Components;

use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\SocialGraph\Actions\UpdateRelationshipVisibility;
use Liberu\SocialNetwork\SocialGraph\Models\Relationship;
use Livewire\Component;

final class RelationshipVisibility extends Component
{
    public string $relationshipId = '';
    public string $visibility = 'followers';

    public function save(GetProfile $get, UpdateRelationshipVisibility $update): void
    {
        $this->validate(['relationshipId' => ['required', 'uuid'], 'visibility' => ['required', 'in:public,followers,private']]);
        $relationship = Relationship::query()->findOrFail($this->relationshipId);
        $update->handle($get->forUser($this->userId()), $relationship, $this->visibility);
        $this->dispatch('social-graph-visibility-updated');
    }

    public function render(): mixed
    {
        return view('social-network-social-graph-livewire::livewire.relationship-visibility');
    }

    private function userId(): int|string
    {
        abort_unless(auth()->check(), 401);
        return auth()->id();
    }
}
