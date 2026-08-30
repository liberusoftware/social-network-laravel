<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Federation\Models;

use Illuminate\Database\Eloquent\Model;

final class FederationMapping extends Model
{
    protected $table = 'social_federation_mappings';

    protected $guarded = [];

    protected function casts(): array
    {
        return ['last_reconciled_at' => 'datetime', 'metadata' => 'array'];
    }
}
