<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Engagement\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Liberu\SocialNetwork\Engagement\Contracts\EngagementAuthorizer;
use Liberu\SocialNetwork\Engagement\Events\EngagementCreated;
use Liberu\SocialNetwork\Engagement\Models\Engagement;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class CreateEngagement
{
    public function __construct(private EngagementAuthorizer $authorizer, private Dispatcher $events) {}

    /** @param array<string, mixed> $attributes */
    public function handle(Profile $actor, array $attributes): Engagement
    {
        $this->authorizer->create($actor);
        $kind = (string) ($attributes['kind'] ?? 'reaction');
        $reaction = $attributes['reaction_type'] ?? null;
        if (! in_array($kind, ['reaction', 'comment', 'reply', 'share', 'bookmark'], true) || ($kind === 'reaction' && ! in_array($reaction, (array) config('social-network-engagement.reaction_types'), true))) {
            throw new InvalidArgumentException('The engagement kind or reaction is not supported.');
        }
        if (trim((string) ($attributes['target_type'] ?? '')) === '' || ! Str::isUuid((string) ($attributes['target_id'] ?? ''))) {
            throw new InvalidArgumentException('Engagements require a valid target reference.');
        }
        if (in_array($kind, ['comment', 'reply'], true) && trim((string) ($attributes['body'] ?? '')) === '') {
            throw new InvalidArgumentException('Comments require body text.');
        }
        if (strlen((string) ($attributes['body'] ?? '')) > (int) config('social-network-engagement.comment_max_length')) {
            throw new InvalidArgumentException('Comment text exceeds the configured limit.');
        }
        $rateKey = 'social-engagement:'.$actor->getKey();
        if (RateLimiter::tooManyAttempts($rateKey, (int) config('social-network-engagement.max_per_minute', 30))) {
            throw new InvalidArgumentException('Engagement rate limit exceeded.');
        }
        RateLimiter::hit($rateKey, 60);
        if (! in_array($kind, ['comment', 'reply'], true) && Engagement::query()->where([
            'actor_profile_id' => $actor->getKey(),
            'target_type' => $attributes['target_type'],
            'target_id' => $attributes['target_id'],
            'kind' => $kind,
            'reaction_type' => $reaction,
        ])->exists()) {
            throw new InvalidArgumentException('This engagement already exists.');
        }
        $engagement = DB::transaction(fn (): Engagement => Engagement::query()->create(['id' => (string) Str::uuid(), 'actor_profile_id' => $actor->getKey(), 'target_type' => (string) ($attributes['target_type'] ?? ''), 'target_id' => (string) ($attributes['target_id'] ?? ''), 'kind' => $kind, 'reaction_type' => $reaction, 'body' => $attributes['body'] ?? null]));
        $this->events->dispatch(new EngagementCreated($engagement));

        return $engagement;
    }
}
