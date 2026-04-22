<?php

namespace App\Http\Controllers;

use App\Enums\AttachmentType;
use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function __construct(
        protected \App\Services\MessageService $messageService
    ) {}

    public function index(Request $request): View
    {
        $user = Auth::user();

        $conversations = Conversation::where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->with(['messages' => function($q) {
                $q->latest()->limit(1);
            }, 'userOne', 'userTwo'])
            ->get()
            ->sortByDesc(function($conversation) {
                return $conversation->messages->first()->created_at ?? $conversation->created_at;
            });

        $activeConversation = null;

        if ($request->has('user_id')) {
            $otherUserId = $request->input('user_id');
            $activeConversation = Conversation::findOrCreateBetween($user->id, $otherUserId);
        }

        return view('pages.messages', compact('conversations', 'activeConversation'));
    }

    public function show(Conversation $conversation): JsonResponse
    {
        $this->authorize('view', $conversation);

        Message::where('conversation_id', $conversation->id)
            ->where('semder_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_reae' => true]);

        Auth::user()->unreadNotifications()
            ->where('data->conversation_id', $conversation->id)
            ->update(['read_at' => now()]);

        $messages = $conversation->messages()->with('sender')->get();

        return response()->json([
            'messages' => $messages,
            'other_user' => $conversation->getOtherUser(Auth::id()),
        ]);
    }

    public function store(\App\Http\Requests\Message\StoreMessageRequest $request, Conversation $conversation): JsonResponse
    {
        $this->authorize('message', $conversation);
        $message = $this->messageService->sendMessage(
            $conversation, 
            Auth::user(), 
            $request->body ?? '', 
            $request->file('attachment')
        );

        return response()->json($message->load('sender'));
    }

    public function checkNewMessages(Conversation $conversation, Request $request): JsonResponse
    {
        $this->authorize('view', $conversation);

        $lastId = $request->input('last_message_id', 0);

        $newMessages = Message::where('conversation_id', $conversation->id)
            ->where('id', '>', $lastId)
            ->with('sender')
            ->get();

        if ($newMessages->isNotEmpty()) {
            Message::where('conversation_id', $conversation->id)
                ->where('sender_id', '!=', Auth::id())
                ->whereIn('id', $newMessages->pluck('id'))
                ->update(['is_read' => true]);
        }

        return response()->json($newMessages);
    }

    public function destroy(Conversation $conversation, Message $message): JsonResponse
    {
        $this->authorize('view', $conversation);
        $this->authorize('delete', $message);

        if ($message->conversation_id !== $conversation->id) {
            abort(404);
        }

        $message->update(['is_deleted' => true]);

        return response()->json(['success' => true, 'message' => $message]);
    }

}
