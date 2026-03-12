<?php

namespace App\Http\Controllers\API\Sabbir;

use App\Events\MessageRead;
use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // ─────────────────────────────────────────────────────────────────
    // STEP 3 — POST /api/conversations/{id}/messages
    // Send text or file — Flutter receives it in real-time via WebSocket
    // ─────────────────────────────────────────────────────────────────
    public function send(Request $request, int $conversationId): JsonResponse
    {
        $request->validate([
            'message_text' => 'nullable|string|max:5000',
            'file'         => 'nullable|file|max:10240|mimes:jpeg,jpg,png,mp4,mov',
        ]);

        if (empty($request->message_text) && !$request->hasFile('file')) {
            return response()->json(['status' => false, 'message' => 'Provide text or file.'], 422);
        }

        $conversation = Conversation::findOrFail($conversationId);

        if (!$conversation->hasUser(auth()->id())) {
            return response()->json(['status' => false, 'message' => 'Unauthorized.'], 403);
        }

        if ($conversation->status !== 'accepted') {
            return response()->json([
                'status'  => false,
                'message' => 'Conversation must be accepted to send messages.',
            ], 422);
        }

        // File upload
        $filePath = null;
        $fileType = null;
        if ($request->hasFile('file')) {
            $file     = $request->file('file');
            $fileType = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
            $filePath = $file->store('chat_files', 'public');
        }

        $message = Message::create([
            'conversation_id' => $conversationId,
            'sender_id'       => auth()->id(),
            'message_text'    => $request->message_text,
            'file_path'       => $filePath,
            'file_type'       => $fileType,
            'is_read'         => false,
        ]);

        $message->load('sender');

        // Update conversation updated_at so it bubbles to top in list
        $conversation->touch();

        // 🔴 BROADCAST → private-conversation.{id}
        // Flutter receives this and appends message to chat in real-time
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'status' => true,
            'data'   => [
                'id'              => $message->id,
                'conversation_id' => $message->conversation_id,
                'sender_id'       => $message->sender_id,
                'sender_name'     => $message->sender->name,
                'message_text'    => $message->message_text,
                'file_url'        => $message->file_url,
                'file_type'       => $message->file_type,
                'is_read'         => $message->is_read,
                'created_at'      => $message->created_at->toISOString(),
            ],
        ], 201);
    }

    // ─────────────────────────────────────────────────────────────────
    // GET /api/conversations/{id}/messages
    // Fetch all messages + mark as read + broadcast read receipt
    // ─────────────────────────────────────────────────────────────────
    public function index(int $conversationId): JsonResponse
    {
        $conversation = Conversation::findOrFail($conversationId);
        $authId       = auth()->id();

        if (!$conversation->hasUser($authId)) {
            return response()->json(['status' => false, 'message' => 'Unauthorized.'], 403);
        }

        // Mark all unread messages from the OTHER user as read
        $updated = Message::where('conversation_id', $conversationId)
            ->where('sender_id', '!=', $authId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // BROADCAST read receipt if any messages were marked read
        if ($updated > 0) {
            broadcast(new MessageRead($conversationId, $authId))->toOthers();
        }

        // Load sender with profile to get avatar
        $messages = Message::with('sender.profile') // profile relation here
            ->where('conversation_id', $conversationId)
            ->oldest()
            ->get()
            ->map(fn($m) => [
                'id'           => $m->id,
                'sender_id'    => $m->sender_id,
                'sender_name'  => $m->sender->name,
                'sender_avatar'=> optional($m->sender->profile)->avatar, // profile.avatar
                'message_text' => $m->message_text,
                'file_url'     => $m->file_url,
                'file_type'    => $m->file_type,
                'is_read'      => $m->is_read,
                'created_at'   => $m->created_at->toISOString(),
            ]);

        return response()->json(['status' => true, 'data' => $messages]);
    }
}
