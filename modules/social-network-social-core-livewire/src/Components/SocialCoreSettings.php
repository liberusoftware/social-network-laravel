<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialCore\Livewire\Components;

use Illuminate\Support\Facades\Gate;
use Liberu\SocialNetwork\SocialCore\Actions\GetSocialNetworkSettings;
use Liberu\SocialNetwork\SocialCore\Actions\UpdateSocialNetworkSettings;
use Livewire\Component;

class SocialCoreSettings extends Component
{
    public string $deploymentMode = 'hosted';

    public string $networkSettings = '{}';

    public string $terminology = '{}';

    public string $featurePolicy = '{}';

    public string $sharedIds = '{}';

    public function mount(GetSocialNetworkSettings $get): void
    {
        $teamId = $this->teamId();
        $settings = $get->handle($teamId);
        $this->deploymentMode = $settings->deployment_mode;
        $this->networkSettings = $this->json($settings->network_settings);
        $this->terminology = $this->json($settings->terminology);
        $this->featurePolicy = $this->json($settings->feature_policy);
        $this->sharedIds = $this->json($settings->shared_ids);
    }

    public function save(UpdateSocialNetworkSettings $update): void
    {
        $this->validate([
            'deploymentMode' => ['required', 'in:hosted,self_hosted,federated'],
            'networkSettings' => ['required', 'json', 'max:10000'],
            'terminology' => ['required', 'json', 'max:10000'],
            'featurePolicy' => ['required', 'json', 'max:10000'],
            'sharedIds' => ['required', 'json', 'max:10000'],
        ]);

        $update->handle($this->teamId(), [
            'deployment_mode' => $this->deploymentMode,
            'network_settings' => $this->decode($this->networkSettings),
            'terminology' => $this->decode($this->terminology),
            'feature_policy' => $this->decode($this->featurePolicy),
            'shared_ids' => $this->decode($this->sharedIds),
        ]);

        $this->dispatch('social-core-settings-saved', teamId: $this->teamId());
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

    private function json(mixed $value): string
    {
        return json_encode($value, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    /** @return array<string, mixed> */
    private function decode(string $value): array
    {
        $decoded = json_decode(
            $value,
            true,
            (int) config('social-network-social-core.maximum_payload_depth', 4),
            JSON_THROW_ON_ERROR,
        );

        abort_unless(is_array($decoded), 422, 'Social Core values must be JSON objects.');

        return $decoded;
    }
}
