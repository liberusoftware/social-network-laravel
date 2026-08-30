<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Liberu\SocialNetwork\Messaging\Authorization\GateMessagingAuthorizer;
use Liberu\SocialNetwork\Messaging\Contracts\MessagingAuthorizer;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class MessagingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/social-network-messaging.php', 'social-network-messaging');
        $this->app->singleton(MessagingAuthorizer::class, GateMessagingAuthorizer::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        Gate::define('social-network.messaging.create', static fn (object $user, Profile $actor): bool => (string) $actor->user_id === (string) $user->getAuthIdentifier());
        Gate::define('social-network.messaging.send', static fn (object $user, Profile $actor): bool => (string) $actor->user_id === (string) $user->getAuthIdentifier());
        Broadcast::channel('social-conversations.{conversation}', static function (object $user, string $conversation): bool {
            return DB::table('social_conversation_members')
                ->where(['conversation_id' => $conversation, 'profile_id' => Profile::query()->where('user_id', $user->getAuthIdentifier())->value('id')])
                ->exists();
        });
    }
}
