<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Moderation\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\SocialNetwork\Moderation\Contracts\ModerationAuthorizer;
use Liberu\SocialNetwork\Moderation\Events\ReportDecided;
use Liberu\SocialNetwork\Moderation\Models\ModerationDecision;
use Liberu\SocialNetwork\Moderation\Models\ModerationReport;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final readonly class DecideReport
{
    public function __construct(private ModerationAuthorizer $authorizer) {}

    public function handle(Profile $actor, ModerationReport $report, string $action, ?string $reason = null, array $evidence = []): ModerationDecision
    {
        $this->authorizer->decide($actor);
        if (! in_array($action, (array) config('social-network-moderation.actions'), true)) {
            throw new InvalidArgumentException('The moderation action is not supported.');
        }
        if (! in_array($report->state, ['open', 'under_review', 'appealed'], true)) {
            throw new InvalidArgumentException('This report is no longer actionable.');
        }
        if ($reason !== null && mb_strlen($reason) > 5000) {
            throw new InvalidArgumentException('The moderation decision reason is too long.');
        }
        if (count($evidence) > 64) {
            throw new InvalidArgumentException('The moderation evidence exceeds the configured limit.');
        }

        $decision = DB::transaction(function () use ($actor, $report, $action, $reason, $evidence): ModerationDecision {
            $report->update(['state' => 'resolved']);

            return ModerationDecision::query()->create(['report_id' => $report->getKey(), 'actor_profile_id' => $actor->getKey(), 'action' => $action, 'reason' => $reason, 'evidence' => $evidence]);
        });
        event(new ReportDecided($decision));

        return $decision;
    }
}
