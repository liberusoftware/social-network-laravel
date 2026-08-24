<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\Publishing\Contracts\PublishingAuthorizer;
use Liberu\SocialNetwork\Publishing\Models\Publication;

final readonly class UpdatePublicationEnrichments
{
    public function __construct(private PublishingAuthorizer $authorizer) {}

    /** @param array<string,mixed> $data */
    public function handle(Profile $author, Publication $publication, array $data): Publication
    {
        $this->authorizer->update($author, $publication);
        if ($publication->state === 'published') {
            throw new InvalidArgumentException('Published publications cannot be enriched.');
        }
        $mentions = array_values(array_unique(array_map('strval', (array) ($data['mentions'] ?? []))));
        $hashtags = array_values(array_unique(array_map(fn ($tag): string => ltrim(strtolower(trim((string) $tag)), '#'), (array) ($data['hashtags'] ?? []))));
        $links = (array) ($data['links'] ?? []);
        if (count($mentions) > 100 || count($hashtags) > 50 || count($links) > 20 || array_filter($hashtags, fn (string $tag): bool => $tag === '' || strlen($tag) > 80) !== []) {
            throw new InvalidArgumentException('Publication enrichments exceed the configured limits.');
        }
        DB::transaction(function () use ($publication, $mentions, $hashtags, $links, $data): void {
            DB::table('social_publication_mentions')->where('publication_id', $publication->getKey())->delete();
            DB::table('social_publication_hashtags')->where('publication_id', $publication->getKey())->delete();
            DB::table('social_publication_links')->where('publication_id', $publication->getKey())->delete();
            foreach ($mentions as $profileId) DB::table('social_publication_mentions')->insert(['publication_id' => $publication->getKey(), 'profile_id' => $profileId, 'created_at' => now(), 'updated_at' => now()]);
            foreach ($hashtags as $tag) DB::table('social_publication_hashtags')->insert(['publication_id' => $publication->getKey(), 'tag' => $tag, 'created_at' => now(), 'updated_at' => now()]);
            foreach ($links as $link) {
                $url = is_array($link) ? ($link['url'] ?? '') : $link;
                if (! filter_var($url, FILTER_VALIDATE_URL) || strlen($url) > 2048) throw new InvalidArgumentException('Each publication link must be a valid URL.');
                DB::table('social_publication_links')->insert(['publication_id' => $publication->getKey(), 'url' => $url, 'title' => is_array($link) ? ($link['title'] ?? null) : null, 'metadata' => is_array($link) ? json_encode($link['metadata'] ?? []) : null, 'created_at' => now(), 'updated_at' => now()]);
            }
            if (array_key_exists('poll', $data)) {
                $poll = (array) $data['poll'];
                $options = array_values(array_filter(array_map('strval', (array) ($poll['options'] ?? []))));
                if (count($options) < 2 || count($options) > 20) throw new InvalidArgumentException('Polls require between 2 and 20 options.');
                DB::table('social_publication_polls')->updateOrInsert(['publication_id' => $publication->getKey()], ['options' => json_encode($options), 'closes_at' => $poll['closes_at'] ?? null, 'created_at' => now(), 'updated_at' => now()]);
            }
        });
        return $publication->refresh();
    }
}
