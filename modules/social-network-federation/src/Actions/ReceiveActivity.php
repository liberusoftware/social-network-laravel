<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Federation\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Liberu\SocialNetwork\Federation\Models\FederationMessage;

final class ReceiveActivity
{
    /** @param array<string, mixed> $payload */
    public function handle(array $payload, ?string $signature = null): FederationMessage
    {
        $encoded = json_encode($payload, JSON_THROW_ON_ERROR);
        abort_if(strlen($encoded) > (int) config('social-network-federation.max_payload_bytes'), 413, 'Federation payload is too large.');

        $type = trim((string) ($payload['type'] ?? ''));
        if ($type === '' || mb_strlen($type) > 80) {
            throw new InvalidArgumentException('The federation activity type is invalid.');
        }

        $remoteId = isset($payload['id']) ? (string) $payload['id'] : null;
        if ($remoteId !== null) {
            $existing = FederationMessage::query()->where('remote_id', $remoteId)->first();
            if ($existing !== null) {
                return $existing;
            }
        }

        return DB::transaction(fn (): FederationMessage => FederationMessage::query()->create([
            'id' => (string) Str::uuid(),
            'direction' => 'inbound',
            'activity_type' => $type,
            'remote_id' => $remoteId,
            'payload' => $payload,
            'signature' => $signature,
            'state' => 'queued',
            'received_at' => now(),
        ]));
    }
}
