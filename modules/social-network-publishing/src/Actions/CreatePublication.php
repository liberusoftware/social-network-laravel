<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\Publishing\Contracts\PublishingAuthorizer;
use Liberu\SocialNetwork\Publishing\Models\Publication;

final readonly class CreatePublication
{
    public function __construct(private PublishingAuthorizer $authorizer) {}

    /** @param array<string, mixed> $attributes */
    public function handle(Profile $author, array $attributes): Publication
    {
        $this->authorizer->create($author);
        $kind = (string) ($attributes['kind'] ?? 'post');
        $audience = (string) ($attributes['audience'] ?? 'public');
        if (! in_array($kind, (array) config('social-network-publishing.kinds'), true) || ! in_array($audience, (array) config('social-network-publishing.audiences'), true)) {
            throw new InvalidArgumentException('The publication kind or audience is not supported.');
        }
        if (count((array) ($attributes['metadata'] ?? [])) > (int) config('social-network-publishing.maximum_metadata', 64)) {
            throw new InvalidArgumentException('Publication metadata exceeds the configured limit.');
        }
        if (trim((string) ($attributes['body'] ?? '')) === '' && trim((string) ($attributes['title'] ?? '')) === '') {
            throw new InvalidArgumentException('A publication requires a title or body.');
        }

        return DB::transaction(fn (): Publication => Publication::query()->create([
            'id' => (string) Str::uuid(), 'author_profile_id' => $author->getKey(), 'kind' => $kind,
            'audience' => $audience, 'title' => $attributes['title'] ?? null, 'body' => $attributes['body'] ?? null,
            'metadata' => $attributes['metadata'] ?? [], 'state' => 'draft',
        ]));
    }
}
