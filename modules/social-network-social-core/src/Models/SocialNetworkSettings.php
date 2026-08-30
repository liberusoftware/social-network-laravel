<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\SocialCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class SocialNetworkSettings extends Model
{
    protected $table = 'social_network_settings';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id', 'team_id', 'deployment_mode', 'network_settings', 'terminology', 'feature_policy', 'shared_ids',
    ];

    protected function casts(): array
    {
        return [
            'network_settings' => 'array',
            'terminology' => 'array',
            'feature_policy' => 'array',
            'shared_ids' => 'array',
        ];
    }

    /** @return BelongsTo<Model, $this> */
    public function team(): BelongsTo
    {
        $teamModel = config('social-network-social-core.team_model');

        if (! is_string($teamModel) || $teamModel === '') {
            throw new \LogicException('The Social Core team model is not configured.');
        }

        return $this->belongsTo($teamModel, 'team_id');
    }
}
