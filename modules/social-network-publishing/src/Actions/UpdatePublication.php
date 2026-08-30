<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\Publishing\Contracts\PublishingAuthorizer;
use Liberu\SocialNetwork\Publishing\Events\PublicationUpdated;
use Liberu\SocialNetwork\Publishing\Models\Publication;

final readonly class UpdatePublication
{
    public function __construct(private PublishingAuthorizer $authorizer, private Dispatcher $events) {}

    /** @param array<string,mixed> $attributes */
    public function handle(Profile $author, Publication $publication, array $attributes): Publication
    {
        $this->authorizer->update($author, $publication);
        if ($publication->state === 'published') {
            throw new InvalidArgumentException('Published publications cannot be edited through this action.');
        }
        if (isset($attributes['kind']) && ! in_array($attributes['kind'], (array) config('social-network-publishing.kinds'), true)) {
            throw new InvalidArgumentException('The publication kind is not supported.');
        }
        if (isset($attributes['audience']) && ! in_array($attributes['audience'], (array) config('social-network-publishing.audiences'), true)) {
            throw new InvalidArgumentException('The publication audience is not supported.');
        }
        if (array_key_exists('scheduled_at', $attributes) && $attributes['scheduled_at'] !== null) {
            try {
                $attributes['scheduled_at'] = Carbon::parse($attributes['scheduled_at']);
            } catch (\Throwable $exception) {
                throw new InvalidArgumentException('The publication schedule must be a valid date.', previous: $exception);
            }
        }
        $updated = DB::transaction(function () use ($author, $publication, $attributes): Publication {
            DB::table('social_publication_edits')->insert([
                'publication_id' => $publication->getKey(), 'editor_profile_id' => $author->getKey(),
                'snapshot' => json_encode($publication->only(['kind', 'audience', 'title', 'body', 'metadata']), JSON_THROW_ON_ERROR),
                'created_at' => now(), 'updated_at' => now(),
            ]);
            $publication->fill(Arr::only($attributes, ['kind', 'audience', 'title', 'body', 'metadata', 'scheduled_at']));
            $publication->save();

            return $publication->refresh();
        });
        $this->events->dispatch(new PublicationUpdated($updated));

        return $updated;
    }
}
