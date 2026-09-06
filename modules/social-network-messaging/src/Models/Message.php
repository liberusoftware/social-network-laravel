<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

final class Message extends Model
{
    use SoftDeletes;

    protected $table = 'social_messages';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'conversation_id', 'sender_profile_id', 'body', 'encrypted_body', 'encryption_key_id', 'state', 'attachments'];

    protected function casts(): array
    {
        return ['attachments' => 'array'];
    }

    public function encryptBody(string $body): void
    {
        $this->encrypted_body = Crypt::encryptString($body);
        $this->encryption_key_id = substr(hash('sha256', (string) config('app.key')), 0, 32);
        $this->body = null;
    }

    public function displayBody(): ?string
    {
        if ($this->encrypted_body === null) {
            return $this->body;
        }

        try {
            return Crypt::decryptString($this->encrypted_body);
        } catch (\Throwable) {
            return null;
        }
    }
}
