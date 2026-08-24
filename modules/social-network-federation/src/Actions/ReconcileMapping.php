<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Federation\Actions;

use Illuminate\Support\Str;
use Liberu\SocialNetwork\Federation\Models\FederationMapping;

final class ReconcileMapping
{
    /** @param array<string, mixed> $metadata */
    public function handle(string $remoteType, string $remoteId, string $localType, string $localId, array $metadata = []): FederationMapping
    {
        return FederationMapping::query()->updateOrCreate(
            ['remote_type' => $remoteType, 'remote_id' => $remoteId],
            [
                'id' => (string) Str::uuid(),
                'local_type' => $localType,
                'local_id' => $localId,
                'state' => 'active',
                'metadata' => $metadata,
                'last_reconciled_at' => now(),
            ],
        );
    }
}
