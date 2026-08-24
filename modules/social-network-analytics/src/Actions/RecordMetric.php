<?php
namespace Liberu\SocialNetwork\Analytics\Actions;
use Liberu\SocialNetwork\Analytics\Contracts\AnalyticsAuthorizer;
use Liberu\SocialNetwork\Analytics\Models\AnalyticsEvent;
final readonly class RecordMetric { public function __construct(private AnalyticsAuthorizer $authorizer) {} public function handle(object $actor, string $name, array $dimensions=[], int $value=1): AnalyticsEvent { $this->authorizer->record($actor,$name); return AnalyticsEvent::query()->create(['name'=>$name,'occurred_on'=>now()->toDateString(),'dimensions'=>$this->redact($dimensions),'value'=>max(0,$value)]); } private function redact(array $dimensions): array { foreach ((array) config('social-network-analytics.private_fields') as $field) unset($dimensions[$field]); return $dimensions; } }
