<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Moderation\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Liberu\SocialNetwork\Moderation\Contracts\ModerationAuthorizer;
use Liberu\SocialNetwork\Moderation\Events\ReportCreated;
use Liberu\SocialNetwork\Moderation\Models\ModerationReport;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class CreateReport
{
    public function __construct(private ModerationAuthorizer $authorizer, private Dispatcher $events) {}

    public function handle(Profile $reporter, string $targetType, string $targetId, string $reason, ?string $details = null): ModerationReport
    {
        $this->authorizer->report($reporter);
        if ($targetType === '' || strlen($targetType) > 160 || ! Str::isUuid($targetId) || trim($reason) === '' || strlen($reason) > 120 || ($details !== null && strlen($details) > 10000)) {
            throw new InvalidArgumentException('A report target and reason are required.');
        }
        if (ModerationReport::query()->where([
            'reporter_profile_id' => $reporter->getKey(),
            'target_type' => $targetType,
            'target_id' => $targetId,
            'state' => 'open',
        ])->exists()) {
            throw new InvalidArgumentException('An open report already exists for this target.');
        }
        $report = DB::transaction(fn (): ModerationReport => ModerationReport::query()->create(['id' => (string) Str::uuid(), 'reporter_profile_id' => $reporter->getKey(), 'target_type' => $targetType, 'target_id' => $targetId, 'reason' => trim($reason), 'details' => $details, 'state' => 'open']));
        $this->events->dispatch(new ReportCreated($report));

        return $report;
    }
}
