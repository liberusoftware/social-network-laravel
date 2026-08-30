<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Feed\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Liberu\SocialNetwork\Feed\Contracts\FeedAuthorizer;
use Liberu\SocialNetwork\Feed\Events\FeedEntryAdded;
use Liberu\SocialNetwork\Feed\Models\FeedEntry;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class AddFeedEntry
{
    public function __construct(private Dispatcher $events, private FeedAuthorizer $authorizer) {}

    public function handle(Profile $viewer, string $itemType, string $itemId, float $rank = 0): FeedEntry
    {
        $this->authorizer->view($viewer);

        if ($itemType === '' || ! Str::isUuid($itemId)) {
            throw new InvalidArgumentException('Feed entries require a valid item reference.');
        }
        if (! is_finite($rank) || $rank < -1000000 || $rank > 1000000) {
            throw new InvalidArgumentException('Feed entry rank is outside the supported range.');
        }
        $entry = DB::transaction(fn (): FeedEntry => FeedEntry::query()->updateOrCreate(['viewer_profile_id' => $viewer->getKey(), 'item_type' => $itemType, 'item_id' => $itemId], ['id' => (string) Str::uuid(), 'rank' => $rank, 'visible_at' => now()]));
        $this->events->dispatch(new FeedEntryAdded($entry));

        return $entry;
    }
}
