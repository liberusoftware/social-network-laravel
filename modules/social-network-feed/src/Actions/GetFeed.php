<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Feed\Actions;

use Illuminate\Database\Eloquent\Collection;
use Liberu\SocialNetwork\Feed\Contracts\FeedAuthorizer;
use Liberu\SocialNetwork\Feed\Models\FeedEntry;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class GetFeed
{
    public function __construct(private FeedAuthorizer $authorizer) {}

    public function handle(Profile $viewer, int $limit = 20, ?string $after = null): Collection
    {
        $this->authorizer->view($viewer);
        $limit = min(max($limit, 1), (int) config('social-network-feed.max_page_size'));
        $query = FeedEntry::query()->where('viewer_profile_id', $viewer->getKey())->where(function ($q): void {
            $q->whereNull('visible_at')->orWhere('visible_at', '<=', now());
        })->orderByDesc('rank')->orderByDesc('id')->limit($limit);
        if ($after !== null) {
            $query->where('id', '<', $after);
        }

        return $query->get();
    }
}
