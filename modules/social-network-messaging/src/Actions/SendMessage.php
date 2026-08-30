<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Liberu\SocialNetwork\Messaging\Contracts\MessagingAuthorizer;
use Liberu\SocialNetwork\Messaging\Events\MessageSent;
use Liberu\SocialNetwork\Messaging\Models\Message;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class SendMessage
{
    public function __construct(private MessagingAuthorizer $authorizer, private Dispatcher $events) {}

    public function handle(Profile $sender, string $conversationId, string $body, array $attachments = [], bool $encrypted = false): Message
    {
        $this->authorizer->send($sender);
        $body = trim($body);
        if ($body === '' && $attachments === []) {
            throw new InvalidArgumentException('A message requires body text or an attachment.');
        }
        if (mb_strlen($body) > (int) config('social-network-messaging.max_body_length')) {
            throw new InvalidArgumentException('Message body is invalid.');
        }
        abort_unless(DB::table('social_conversation_members')->where(['conversation_id' => $conversationId, 'profile_id' => $sender->getKey()])->exists(), 403);
        if (count($attachments) > 20) {
            throw new InvalidArgumentException('Too many message attachments.');
        }
        if ($encrypted && ! (bool) config('social-network-messaging.allow_encryption', true)) {
            throw new InvalidArgumentException('Encrypted messaging is disabled.');
        }

        $message = DB::transaction(function () use ($sender, $conversationId, $body, $attachments, $encrypted): Message {
            $message = new Message([
                'id' => (string) Str::uuid(),
                'conversation_id' => $conversationId,
                'sender_profile_id' => $sender->getKey(),
                'body' => $body,
                'state' => 'sent',
                'attachments' => $attachments,
            ]);

            if ($encrypted) {
                $message->encryptBody($body);
            }

            $message->save();

            return $message;
        });
        $this->events->dispatch(new MessageSent($message));

        return $message;
    }
}
