<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Publishing\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Liberu\SocialNetwork\Profiles\Models\Profile;
use Liberu\SocialNetwork\Publishing\Contracts\PublishingAuthorizer;
use Liberu\SocialNetwork\Publishing\Events\PublicationPublished;
use Liberu\SocialNetwork\Publishing\Models\Publication;

final readonly class PublishPublication
{
    public function __construct(private PublishingAuthorizer $authorizer, private Dispatcher $events) {}

    public function handle(Profile $author, Publication $publication): Publication
    {
        $this->authorizer->publish($author, $publication);
        $published = DB::transaction(function () use ($publication): Publication {
            $publication->update(['state' => 'published', 'published_at' => Carbon::now()]);

            return $publication->refresh();
        });
        $this->events->dispatch(new PublicationPublished($published));

        return $published;
    }
}
