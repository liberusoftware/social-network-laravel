<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Liberu\SocialNetwork\Messaging\Contracts\MessagingAuthorizer;
use Liberu\SocialNetwork\Messaging\Events\MessageReactionAdded;
use Liberu\SocialNetwork\Messaging\Models\Message;
use Liberu\SocialNetwork\Messaging\Models\MessageReaction;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class AddReaction
{
    public function __construct(
        private MessagingAuthorizer $authorizer,
        private Dispatcher $events,
    ) {}

    public function handle(Profile $profile, Message $message, string $emoji): MessageReaction
    {
        $this->authorizer->send($profile);
        $emoji = trim($emoji);
        if ($emoji === '' || mb_strlen($emoji) > 32) {
            throw new InvalidArgumentException('The message reaction is invalid.');
        }
        abort_unless(DB::table('social_conversation_members')->where([
            'conversation_id' => $message->conversation_id,
            'profile_id' => $profile->getKey(),
        ])->exists(), 403);

        $reaction = MessageReaction::query()->firstOrCreate(
            ['message_id' => $message->getKey(), 'profile_id' => $profile->getKey(), 'emoji' => $emoji],
            ['id' => (string) Str::uuid(), 'conversation_id' => $message->conversation_id],
        );
        $this->events->dispatch(new MessageReactionAdded($reaction));

        return $reaction;
    }
}
