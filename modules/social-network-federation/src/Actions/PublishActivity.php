<?php
namespace Liberu\SocialNetwork\Federation\Actions;
use Illuminate\Support\Str;
use Liberu\SocialNetwork\Federation\Models\FederationMessage;
final class PublishActivity { public function handle(string $type, array $payload): FederationMessage { return FederationMessage::query()->create(['id'=>(string) Str::uuid(),'direction'=>'outbound','activity_type'=>$type,'remote_id'=>$payload['id']??null,'payload'=>$payload,'state'=>'queued']); } }
