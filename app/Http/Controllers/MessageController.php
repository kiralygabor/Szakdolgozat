<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get all conversations for this user, ordered by latest message
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
        $messages = [];

        // If a specific user is requested (e.g. from "Message" button)
        if ($request->has('user_id')) {
            $otherUserId = $request->input('user_id');
            $activeConversation = $this->getOrCreateConversation($user->id, $otherUserId);
            // Redirect to clean URL with conversation ID or just load it
        }

        return view('pages.messages', compact('conversations', 'activeConversation'));
    }

    public function show(Conversation $conversation)
    {
        $this->authorizeConversation($conversation);
        
        // Mark messages as read
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $conversation->messages()->with('sender')->get();
        
        return response()->json([
            'messages' => $messages,
            'other_user' => $conversation->getOtherUser(Auth::id())
        ]);
    }

    public function store(Request $request, Conversation $conversation)
    {
        $this->authorizeConversation($conversation);

        $request->validate([
            'body' => 'nullable|string|max:5000',
            'attachment' => 'nullable|file|max:10240|mimes:jpeg,png,jpg,gif,pdf,doc,docx', // Max 10MB
        ]);

        if (!$request->body && !$request->hasFile('attachment')) {
            return response()->json(['error' => 'Message cannot be empty'], 422);
        }

        $attachmentPath = null;
        $attachmentType = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentPath = $file->store('message-attachments', 'public');
            $attachmentType = $file->getClientOriginalExtension();
            
            // Simple check for image types
            if (in_array(strtolower($attachmentType), ['jpg', 'jpeg', 'png', 'gif'])) {
                $attachmentType = 'image';
            } else {
                $attachmentType = 'file';
            }
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'body' => $request->body,
            'attachment' => $attachmentPath,
            'attachment_type' => $attachmentType,
            'is_read' => false
        ]);

        return response()->json($message->load('sender'));
    }

    // API for polling
    public function checkNewMessages(Conversation $conversation, Request $request)
    {
        $this->authorizeConversation($conversation);
        
        $lastId = $request->input('last_message_id', 0);
        
        $newMessages = Message::where('conversation_id', $conversation->id)
            ->where('id', '>', $lastId)
            ->with('sender')
            ->get();
            
        // Mark as read if they are from the other person
        if ($newMessages->isNotEmpty()) {
             Message::where('conversation_id', $conversation->id)
                ->where('sender_id', '!=', Auth::id())
                ->whereIn('id', $newMessages->pluck('id'))
                ->update(['is_read' => true]);
        }

        return response()->json($newMessages);
    }

    public function destroy(Conversation $conversation, Message $message)
    {
        $this->authorizeConversation($conversation);

        if ($message->conversation_id !== $conversation->id) {
            abort(404);
        }

        if ($message->sender_id !== Auth::id()) {
            abort(403, 'You can only delete your own messages.');
        }

        $message->update(['is_deleted' => true]);

        return response()->json(['success' => true, 'message' => $message]);
    }

    // Helper to find or create conversation
    private function getOrCreateConversation($userId, $otherUserId)
    {
        // Ensure consistent ordering to find existing
        $userOne = min($userId, $otherUserId);
        $userTwo = max($userId, $otherUserId);

        $conversation = Conversation::where('user_one_id', $userOne)
            ->where('user_two_id', $userTwo)
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => $userOne,
                'user_two_id' => $userTwo
            ]);
        }

        return $conversation;
    }

    private function authorizeConversation($conversation)
    {
        if ($conversation->user_one_id !== Auth::id() && $conversation->user_two_id !== Auth::id()) {
            abort(403);
        }
    }
}
