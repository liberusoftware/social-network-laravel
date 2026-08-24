<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Filament\Pages;

use Filament\Pages\Page;
use Liberu\SocialNetwork\Messaging\Actions\ListConversations;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\Messaging\Models\Conversation;

final class Conversations extends Page
{
    protected string $view = 'social-network-messaging-filament::pages.conversations';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static string|\UnitEnum|null $navigationGroup = 'Social Network';

    public function conversations(GetProfile $get, ListConversations $list): mixed
    {
        abort_unless(auth()->check(), 404);
        return $list->handle($get->forUser(auth()->id()), 50);
    }
}
