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

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(int|string $teamId, array $attributes, int|string|null $actorId = null): SocialNetworkSettings
    {
        $this->authorizer->authorize($teamId);

        $this->validateAttributes($attributes);

        [$settings, $before, $after] = DB::transaction(function () use ($teamId, $attributes): array {
            $settings = $this->settings->forTeam($teamId);
            $before = $settings->only([
                'deployment_mode', 'network_settings', 'terminology', 'feature_policy', 'shared_ids',
            ]);
            $settings->fill(Arr::only($attributes, [
                'deployment_mode', 'network_settings', 'terminology', 'feature_policy', 'shared_ids',
            ]));
            $settings->save();

            $settings = $settings->refresh();

            return [$settings, $before, $settings->only(array_keys($before))];
        });

        $this->events->dispatch(new SocialNetworkSettingsUpdated($settings, $before, $after, $actorId));

        return $settings;
    }

    /** @param array<string, mixed> $attributes */
    private function validateAttributes(array $attributes): void
    {
        $allowed = ['deployment_mode', 'network_settings', 'terminology', 'feature_policy', 'shared_ids'];
        $unknown = array_diff(array_keys($attributes), $allowed);

        if ($unknown !== []) {
            throw new InvalidArgumentException('Unsupported Social Core setting: '.implode(', ', $unknown));
        }

        $mode = $attributes['deployment_mode'] ?? null;
        if ($mode !== null && (! is_string($mode) || ! in_array($mode, (array) config('social-network-social-core.allowed_deployment_modes'), true))) {
            throw new InvalidArgumentException('The selected deployment mode is not supported.');
        }

        foreach (['network_settings', 'terminology', 'feature_policy', 'shared_ids'] as $field) {
            if (array_key_exists($field, $attributes) && ! is_array($attributes[$field])) {
                throw new InvalidArgumentException("The {$field} value must be an array.");
            }

            if (is_array($attributes[$field] ?? null) && count($attributes[$field]) > (int) config('social-network-social-core.maximum_payload_keys', 64)) {
                throw new InvalidArgumentException("The {$field} value contains too many entries.");
            }

            if (array_key_exists($field, $attributes) && is_array($attributes[$field])) {
                $this->assertDepth($attributes[$field], 1);
            }
        }
    }

    /** @param array<mixed> $value */
    private function assertDepth(array $value, int $depth): void
    {
        $maximumDepth = (int) config('social-network-social-core.maximum_payload_depth', 4);

        foreach ($value as $nested) {
            if (! is_array($nested)) {
                continue;
            }

            if ($depth >= $maximumDepth) {
                throw new InvalidArgumentException('Social Core values exceed the maximum nesting depth.');
            }

            $this->assertDepth($nested, $depth + 1);
        }
    }
}
