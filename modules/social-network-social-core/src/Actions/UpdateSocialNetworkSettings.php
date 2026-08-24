<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialCore\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\SocialNetwork\SocialCore\Contracts\SocialNetworkSettingsAuthorizer;
use Liberu\SocialNetwork\SocialCore\Contracts\SocialNetworkSettingsRepository;
use Liberu\SocialNetwork\SocialCore\Events\SocialNetworkSettingsUpdated;
use Liberu\SocialNetwork\SocialCore\Models\SocialNetworkSettings;

final readonly class UpdateSocialNetworkSettings
{
    public function __construct(
        private SocialNetworkSettingsRepository $settings,
        private SocialNetworkSettingsAuthorizer $authorizer,
        private Dispatcher $events,
    ) {}

    /** @param array<string, mixed> $attributes */
    public function handle(int|string $teamId, array $attributes): SocialNetworkSettings
    {
        $this->authorizer->authorize($teamId);

        $mode = $attributes['deployment_mode'] ?? null;
        if ($mode !== null && ! in_array($mode, (array) config('social-network-social-core.allowed_deployment_modes'), true)) {
            throw new InvalidArgumentException('The selected deployment mode is not supported.');
        }

        $settings = DB::transaction(function () use ($teamId, $attributes): SocialNetworkSettings {
            $settings = $this->settings->forTeam($teamId);
            $settings->fill(Arr::only($attributes, [
                'deployment_mode', 'network_settings', 'terminology', 'feature_policy', 'shared_ids',
            ]));
            $settings->save();

            return $settings->refresh();
        });

        $this->events->dispatch(new SocialNetworkSettingsUpdated($settings));

        return $settings;
    }
}
