<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Federation\Actions;

use Illuminate\Support\Str;
use Liberu\SocialNetwork\Federation\Models\FederationDelivery;
use Liberu\SocialNetwork\Federation\Models\FederationMessage;

final class QueueDelivery
{
    public function handle(FederationMessage $message, string $inboxUrl): FederationDelivery
    {
        return FederationDelivery::query()->firstOrCreate(
            ['message_id' => $message->getKey(), 'inbox_url' => $inboxUrl],
            ['id' => (string) Str::uuid(), 'state' => 'queued', 'attempts' => 0],
        );
    }
}
