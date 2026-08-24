<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Federation\Actions;

use InvalidArgumentException;

final class VerifyActivitySignature
{
    public function handle(string $payload, string $signature, string $publicKey): bool
    {
        $encoded = base64_decode($signature, true);
        if ($encoded === false || $publicKey === '') {
            throw new InvalidArgumentException('The federation signature is invalid.');
        }

        return openssl_verify($payload, $encoded, $publicKey, OPENSSL_ALGO_SHA256) === 1;
    }
}
