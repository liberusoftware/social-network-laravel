<?php
declare(strict_types=1);
namespace Liberu\SocialNetwork\Events\Actions;
use Illuminate\Database\Eloquent\Collection; use Liberu\SocialNetwork\Events\Models\Event; use Liberu\SocialNetwork\Profiles\Models\Profile;
final class ListEvents { public function handle(Profile $viewer,int $limit=25): Collection { return Event::query()->whereIn('state',['published','completed'])->orWhere('owner_profile_id',$viewer->getKey())->orderBy('starts_at')->limit(max(1,min($limit,100)))->get(); } }
