<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Messaging\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Liberu\SocialNetwork\Messaging\Actions\AddParticipant;
use Liberu\SocialNetwork\Messaging\Actions\AddReaction;
use Liberu\SocialNetwork\Messaging\Actions\CreateConversation;
use Liberu\SocialNetwork\Messaging\Actions\DeleteMessage;
use Liberu\SocialNetwork\Messaging\Actions\GetConversation;
use Liberu\SocialNetwork\Messaging\Actions\ListConversations;
use Liberu\SocialNetwork\Messaging\Actions\MarkConversationRead;
use Liberu\SocialNetwork\Messaging\Actions\RemoveParticipant;
use Liberu\SocialNetwork\Messaging\Actions\RemoveReaction;
use Liberu\SocialNetwork\Messaging\Actions\SendMessage;
use Liberu\SocialNetwork\Messaging\Actions\UnreadMessageCount;
use Liberu\SocialNetwork\Messaging\Events\UserTyping;
use Liberu\SocialNetwork\Messaging\Models\Message;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
use Liberu\SocialNetwork\Profiles\Models\Profile;

final class MessagingController extends Controller
{
    public function conversation(Request $request, GetProfile $get, CreateConversation $create): JsonResponse
    {
        $data = $request->validate(['title' => ['nullable', 'string', 'max:200'], 'participant_profile_ids' => ['sometimes', 'array', 'min:1'], 'participant_profile_ids.*' => ['uuid', 'exists:social_profiles,id']]);
        $conversation = $create->handle($get->forUser($request->user()->getAuthIdentifier()), $data['title'] ?? null, $data['participant_profile_ids'] ?? []);

        return response()->json(['data' => ['id' => $conversation->getKey(), 'type' => 'social-network-conversations', 'state' => $conversation->state, 'title' => $conversation->title]], 201);
    }

    public function index(Request $request, GetProfile $get, ListConversations $list): JsonResponse
    {
        $data = $request->validate(['limit' => ['sometimes', 'integer', 'min:1', 'max:100']]);
        $items = $list->handle($get->forUser($request->user()->getAuthIdentifier()), $data['limit'] ?? 25);

        return response()->json(['data' => $items->map(fn ($conversation): array => [
            'id' => $conversation->getKey(), 'type' => 'social-network-conversations',
            'state' => $conversation->state, 'title' => $conversation->title,
        ])->values()]);
    }

    public function unreadCount(Request $request, GetProfile $get, UnreadMessageCount $count): JsonResponse
    {
        return response()->json(['count' => $count->handle($get->forUser($request->user()->getAuthIdentifier()))]);
    }

    public function show(string $conversation, Request $request, GetProfile $get, GetConversation $view): JsonResponse
    {
        $item = $view->handle($get->forUser($request->user()->getAuthIdentifier()), $conversation);

        return response()->json(['data' => ['id' => $item->getKey(), 'type' => 'social-network-conversations', 'state' => $item->state, 'title' => $item->title, 'participants' => $item->members->map(fn (Profile $profile): array => ['id' => $profile->getKey(), 'handle' => $profile->handle])->values(), 'messages' => $item->messages->map(fn (Message $message): array => $this->messageResource($message))->values()]]);
    }

    public function messages(string $conversation, Request $request, GetProfile $get, GetConversation $view): JsonResponse
    {
        $item = $view->handle($get->forUser($request->user()->getAuthIdentifier()), $conversation);

        return response()->json(['data' => $item->messages->map(fn (Message $message): array => $this->messageResource($message))->values()]);
    }

    public function message(string $conversation, Request $request, GetProfile $get, SendMessage $send): JsonResponse
    {
        $data = $request->validate([
            'body' => ['nullable', 'string', 'max:10000'],
            'attachments' => ['sometimes', 'array', 'max:20'],
            'attachments.*' => ['array'],
            'encrypted' => ['sometimes', 'boolean'],
        ]);
        $message = $send->handle($get->forUser($request->user()->getAuthIdentifier()), $conversation, $data['body'] ?? '', $data['attachments'] ?? [], (bool) ($data['encrypted'] ?? false));

        return response()->json(['data' => [
            'id' => $message->getKey(), 'type' => 'social-network-messages',
            'conversation_id' => $message->conversation_id, 'body' => $message->displayBody(),
            'state' => $message->state, 'attachments' => $message->attachments,
        ]], 201);
    }

    public function reaction(string $conversation, Message $message, Request $request, GetProfile $get, AddReaction $add): JsonResponse
    {
        abort_unless((string) $message->conversation_id === $conversation, 404);
        $data = $request->validate(['emoji' => ['required', 'string', 'max:32']]);
        $reaction = $add->handle($get->forUser($request->user()->getAuthIdentifier()), $message, $data['emoji']);

        return response()->json(['data' => $reaction], 201);
    }

    public function removeReaction(string $conversation, Message $message, string $emoji, Request $request, GetProfile $get, RemoveReaction $remove): JsonResponse
    {
        abort_unless((string) $message->conversation_id === $conversation, 404);
        $remove->handle($get->forUser($request->user()->getAuthIdentifier()), $message, $emoji);

        return response()->json(status: 204);
    }

    public function typing(string $conversation, Request $request, GetProfile $get): JsonResponse
    {
        $profile = $get->forUser($request->user()->getAuthIdentifier());
        abort_unless(DB::table('social_conversation_members')->where(['conversation_id' => $conversation, 'profile_id' => $profile->getKey()])->exists(), 403);
        broadcast(new UserTyping($conversation, (string) $profile->getKey()))->toOthers();

        return response()->json(['data' => ['conversation_id' => $conversation, 'profile_id' => $profile->getKey()]]);
    }

    public function read(string $conversation, Request $request, GetProfile $get, MarkConversationRead $read): JsonResponse
    {
        $read->handle($get->forUser($request->user()->getAuthIdentifier()), $conversation);

        return response()->json(status: 204);
    }

    public function destroyMessage(string $conversation, string $message, Request $request, GetProfile $get, DeleteMessage $delete): JsonResponse
    {
        $delete->handle($get->forUser($request->user()->getAuthIdentifier()), $conversation, Message::query()->findOrFail($message));

        return response()->json(status: 204);
    }

    public function addParticipant(string $conversation, Request $request, GetProfile $get, AddParticipant $add): JsonResponse
    {
        $data = $request->validate(['profile_id' => ['required', 'uuid', 'exists:social_profiles,id']]);
        $add->handle($get->forUser($request->user()->getAuthIdentifier()), $conversation, Profile::query()->findOrFail($data['profile_id']));

        return response()->json(status: 204);
    }

    public function removeParticipant(string $conversation, string $profile, Request $request, GetProfile $get, RemoveParticipant $remove): JsonResponse
    {
        $remove->handle($get->forUser($request->user()->getAuthIdentifier()), $conversation, Profile::query()->findOrFail($profile));

        return response()->json(status: 204);
    }

    private function messageResource(Message $message): array
    {
        return ['id' => $message->getKey(), 'type' => 'social-network-messages', 'conversation_id' => $message->conversation_id, 'sender_profile_id' => $message->sender_profile_id, 'body' => $message->displayBody(), 'state' => $message->state, 'attachments' => $message->attachments];
    }
}
