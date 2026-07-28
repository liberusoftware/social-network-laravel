<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Group;
use App\Models\Message;
use App\Models\Post;
use App\Models\Team;
use App\Models\User;
use App\Policies\GroupPolicy;
use App\Policies\MessagePolicy;
use App\Policies\PostPolicy;
use App\Policies\TeamPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    #[\Override]
    protected $policies = [
        User::class => UserPolicy::class,
        Group::class => GroupPolicy::class,
        Post::class => PostPolicy::class,
        Message::class => MessagePolicy::class,
        Team::class => TeamPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
