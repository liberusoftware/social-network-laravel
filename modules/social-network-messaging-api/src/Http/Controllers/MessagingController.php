<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\SocialNetwork\Messaging\Actions\CreateConversation;
use Liberu\SocialNetwork\Messaging\Actions\MarkConversationRead;
use Liberu\SocialNetwork\Messaging\Actions\SendMessage;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;

final class MessagingController extends Controller
{
    public function conversation(Request $request, GetProfile $get, CreateConversation $create): JsonResponse
    {
        $data = $request->validate(['title' => ['nullable', 'string', 'max:200']]);
        $c = $create->handle($get->forUser($request->user()->getAuthIdentifier()), $data['title'] ?? null);

        return response()->json(['data' => ['id' => $c->getKey(), 'type' => 'social-network-conversations', 'state' => $c->state, 'title' => $c->title]], 201);
    }

    public function message(string $conversation, Request $request, GetProfile $get, SendMessage $send): JsonResponse
    {
        $data = $request->validate(['body' => ['required', 'string', 'max:10000'], 'attachments' => ['sometimes', 'array']]);
        $m = $send->handle($get->forUser($request->user()->getAuthIdentifier()), $conversation, $data['body'], $data['attachments'] ?? []);

        return response()->json(['data' => ['id' => $m->getKey(), 'type' => 'social-network-messages', 'conversation_id' => $m->conversation_id, 'body' => $m->body, 'state' => $m->state]], 201);
    }

    public function read(string $conversation, Request $request, GetProfile $get, MarkConversationRead $read): JsonResponse
    {
        $read->handle($get->forUser($request->user()->getAuthIdentifier()), $conversation);

        return response()->json(status: 204);
    }
}
