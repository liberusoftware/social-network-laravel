<?php

use Illuminate\Support\Facades\Event;
use Liberu\SocialNetwork\Analytics\Actions\RecordMetric;
use Liberu\SocialNetwork\Analytics\Contracts\AnalyticsAuthorizer;
use Liberu\SocialNetwork\Analytics\Events\MetricRecorded;

use function Pest\Laravel\mock;

it('records bounded privacy-redacted analytics dimensions and emits after-commit evidence', function (): void {
    Event::fake();
    $authorizer = mock(AnalyticsAuthorizer::class);
    $authorizer->shouldReceive('record')->once();

    $event = (new RecordMetric($authorizer, app('events')))->handle(new stdClass(), 'growth.signups', [
        'country' => 'GB',
        'user_id' => 'private',
        'nested' => ['email' => 'private', 'source' => 'campaign'],
    ], 3);

    expect($event->name)->toBe('growth.signups')
        ->and($event->dimensions)->toBe([
            'country' => 'GB',
            'nested' => ['source' => 'campaign'],
        ])
        ->and($event->value)->toBe(3);

    Event::assertDispatched(MetricRecorded::class);
});

it('rejects metric names outside the public analytics contract', function (): void {
    $authorizer = mock(AnalyticsAuthorizer::class);

    expect(fn (): mixed => (new RecordMetric($authorizer, app('events')))->handle(new stdClass(), 'PII metric'))
        ->toThrow(InvalidArgumentException::class);
});
