<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Feed\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\SocialNetwork\Feed\Contracts\FeedAuthorizer;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class UpdateFeedControls
{
    public function __construct(private FeedAuthorizer $authorizer) {}

    /** @param array<string,mixed> $data */
    public function handle(Profile $profile, array $data): array
    {
        $this->authorizer->view($profile);
        $mode = $data['mode'] ?? 'ranked';
        if (! in_array($mode, ['ranked', 'chronological'], true)) {
            throw new InvalidArgumentException('The feed mode is not supported.');
        }
        $filters = array_values(array_unique(array_filter(array_map('strval', (array) ($data['filters'] ?? [])), static fn (string $value): bool => $value !== '')));
        $hidden = array_values(array_unique(array_filter(array_map('strval', (array) ($data['hidden_items'] ?? [])), static fn (string $value): bool => $value !== '')));
        if (count($filters) > 20 || count($hidden) > 500) {
            throw new InvalidArgumentException('Feed controls exceed the configured limits.');
        }
        $existing = DB::table('social_feed_controls')->where('profile_id', $profile->getKey())->exists();
        DB::table('social_feed_controls')->updateOrInsert(['profile_id' => $profile->getKey()], ['mode' => $mode, 'filters' => json_encode($filters, JSON_THROW_ON_ERROR), 'hidden_items' => json_encode($hidden, JSON_THROW_ON_ERROR), 'updated_at' => now(), 'created_at' => $existing ? DB::raw('created_at') : now()]);

        return ['mode' => $mode, 'filters' => $filters, 'hidden_items' => $hidden];
    }
}
