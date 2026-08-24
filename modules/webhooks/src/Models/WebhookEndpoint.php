<?php

declare(strict_types=1);

namespace Liberu\Foundation\Webhooks\Models;

use Illuminate\Database\Eloquent\Model;

final class WebhookEndpoint extends Model
{
    protected $table = 'webhook_endpoints';

    protected $fillable = ['owner_ref', 'url', 'signing_secret', 'events', 'active', 'rotated_at'];

    protected function casts(): array
    {
        return ['events' => 'array', 'active' => 'boolean', 'rotated_at' => 'datetime'];
    }
}
