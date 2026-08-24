<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Federation\Models;

use Illuminate\Database\Eloquent\Model;

final class FederationDelivery extends Model
{
    protected $table = 'social_federation_deliveries';

    protected $guarded = [];

    protected function casts(): array
    {
        return ['delivered_at' => 'datetime'];
    }
}
