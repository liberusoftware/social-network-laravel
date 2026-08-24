<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\SocialNetwork\Messaging\Actions\CreateConversation;
use Liberu\SocialNetwork\Messaging\Actions\MarkConversationRead;
use Liberu\SocialNetwork\Messaging\Actions\ListConversations;
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

    public function index(Request $request, GetProfile $get, ListConversations $list): JsonResponse
    {
        $data = $request->validate(['limit' => ['sometimes', 'integer', 'min:1', 'max:100']]);
        $items = $list->handle($get->forUser($request->user()->getAuthIdentifier()), $data['limit'] ?? 25);
        return response()->json(['data' => $items->map(fn ($c): array => ['id' => $c->getKey(), 'type' => 'social-network-conversations', 'state' => $c->state, 'title' => $c->title])->values()]);
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
