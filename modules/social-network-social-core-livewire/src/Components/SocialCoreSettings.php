<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialCore\Livewire\Components;

use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\SocialCore\Actions\GetSocialNetworkSettings;
use Liberu\SocialNetwork\SocialCore\Actions\UpdateSocialNetworkSettings;
use Livewire\Component;

final class SocialCoreSettings extends Component
{
    public string $deploymentMode = 'hosted';

    /** @var array<string, mixed> */
    public array $networkSettings = [];

    /** @var array<string, mixed> */
    public array $terminology = [];

    /** @var array<string, mixed> */
    public array $featurePolicy = [];

    /** @var array<string, mixed> */
    public array $sharedIds = [];

    public function mount(GetSocialNetworkSettings $get): void
    {
        $teamId = $this->teamId();
        $settings = $get->handle($teamId);
        $this->deploymentMode = $settings->deployment_mode;
        $this->networkSettings = $settings->network_settings;
        $this->terminology = $settings->terminology;
        $this->featurePolicy = $settings->feature_policy;
        $this->sharedIds = $settings->shared_ids;
    }

    public function save(UpdateSocialNetworkSettings $update): void
    {
        $this->validate([
            'deploymentMode' => ['required', 'in:hosted,self_hosted,federated'],
            'networkSettings' => ['array'],
            'terminology' => ['array'],
            'featurePolicy' => ['array'],
            'sharedIds' => ['array'],
        ]);

        $update->handle($this->teamId(), [
            'deployment_mode' => $this->deploymentMode,
            'network_settings' => $this->networkSettings,
            'terminology' => $this->terminology,
            'feature_policy' => $this->featurePolicy,
            'shared_ids' => $this->sharedIds,
        ]);

        $this->dispatch('social-core-settings-saved');
    }

    public function render(): mixed
    {
        return view('social-network-social-core-livewire::livewire.social-core-settings');
    }

    private function teamId(): int|string
    {
        $teamId = auth()->user()?->current_team_id;
        abort_unless($teamId !== null && Gate::allows('social-network.social-core.view', [$teamId]), 404);

        return $teamId;
    }
}
