<?php

use Illuminate\Support\Facades\Crypt;
use Liberu\SocialNetwork\Messaging\Models\Message;

it('keeps encrypted message bodies out of persisted plaintext and decrypts them for authorized presentation', function (): void {
    config()->set('app.key', 'base64:'.base64_encode(str_repeat('a', 32)));

    $message = new Message(['body' => 'private message']);
    $message->encryptBody('private message');

    expect($message->body)->toBeNull()
        ->and($message->encrypted_body)->toBeString()->not->toBe('private message')
        ->and($message->displayBody())->toBe('private message')
        ->and(Crypt::decryptString($message->encrypted_body))->toBe('private message');
});

it('fails closed when an encrypted body can no longer be decrypted', function (): void {
    config()->set('app.key', 'base64:'.base64_encode(str_repeat('b', 32)));

    $message = new Message(['encrypted_body' => 'not-valid-ciphertext']);

    expect($message->displayBody())->toBeNull();
});
