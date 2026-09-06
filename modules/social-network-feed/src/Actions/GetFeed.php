<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Feed\Actions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
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
        $controls = DB::table('social_feed_controls')->where('profile_id', $viewer->getKey())->first();
        $mode = $controls?->mode ?? 'ranked';
        $filters = $controls?->filters ? json_decode($controls->filters, true, 512, JSON_THROW_ON_ERROR) : [];
        $hidden = $controls?->hidden_items ? json_decode($controls->hidden_items, true, 512, JSON_THROW_ON_ERROR) : [];
        $filters = is_array($filters) ? array_values(array_filter($filters, 'is_string')) : [];
        $hidden = is_array($hidden) ? array_values(array_filter($hidden, 'is_string')) : [];
        $query = FeedEntry::query()->where('viewer_profile_id', $viewer->getKey())->where(function ($q): void {
            $q->whereNull('visible_at')->orWhere('visible_at', '<=', now());
        })->when($filters !== [], fn ($q) => $q->whereIn('item_type', $filters))->when($hidden !== [], fn ($q) => $q->whereNotIn('item_id', $hidden));
        if ($mode === 'chronological') {
            $query->orderByDesc('created_at');
        } else {
            $query->orderByDesc('rank');
        }
        $query->orderByDesc('id')->limit($limit);
        if ($after !== null) {
            $query->where('id', '<', $after);
        }

        return $query->get();
    }
}
