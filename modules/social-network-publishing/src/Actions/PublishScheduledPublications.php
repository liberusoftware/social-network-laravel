<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Liberu\SocialNetwork\Publishing\Events\PublicationPublished;
use Liberu\SocialNetwork\Publishing\Models\Publication;

final readonly class PublishScheduledPublications
{
    public function __construct(private Dispatcher $events) {}

    public function handle(?Carbon $now = null, int $limit = 100): int
    {
        $now ??= Carbon::now();
        $published = 0;

        do {
            $batch = DB::transaction(function () use ($now, $limit): array {
                $items = Publication::query()
                    ->where('state', 'draft')
                    ->whereNotNull('scheduled_at')
                    ->where('scheduled_at', '<=', $now)
                    ->orderBy('scheduled_at')
                    ->limit($limit)
                    ->lockForUpdate()
                    ->get();

                foreach ($items as $publication) {
                    $publication->update([
                        'state' => 'published',
                        'published_at' => $now,
                    ]);
                }

                return $items->all();
            });

            foreach ($batch as $publication) {
                $published++;
                $this->events->dispatch(new PublicationPublished($publication->refresh()));
            }
        } while (count($batch) === $limit);

        return $published;
    }
}
