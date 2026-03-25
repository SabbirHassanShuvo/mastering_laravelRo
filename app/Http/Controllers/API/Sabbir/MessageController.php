<?php

namespace App\Http\Controllers\API\Sabbir;

use App\Events\MessageRead;
use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\ContactShare;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Pickup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Storage;

class MessageController extends Controller
{
    // ─────────────────────────────────────────────────────────────────
    // STEP 3 — POST /api/conversations/{id}/messages
    // Send text or file — Flutter receives it in real-time via WebSocket
    // ─────────────────────────────────────────────────────────────────
    public function send(Request $request, int $conversationId): JsonResponse
    {
        // Allow strings for potential base64 uploads and files for multipart
        $request->validate([
            'message_text' => 'nullable|string|max:5000',
            'file'         => 'nullable',
            'image'        => 'nullable',
            'video'        => 'nullable',
        ]);

        $file = $request->file('file') ?? $request->file('image') ?? $request->file('video');

        // If no file found in specific keys, pick the first file from the request
        if (!$file && count($request->allFiles()) > 0) {
            $file = array_values($request->allFiles())[0];
        }

        if (empty($request->message_text) && !$file && !$request->filled('image') && !$request->filled('file')) {
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

        // File upload handling
        $filePath = null;
        $fileType = null;

        if ($file) {
            $fileType = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';
            $fileName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('chat_files', $fileName, 'public');
        } elseif ($request->filled('image') && is_string($request->image) && str_starts_with($request->image, 'data:image')) {
            // Handle Base64 Image
            $fileData = $request->image;
            $extension = explode('/', explode(':', substr($fileData, 0, strpos($fileData, ';')))[1])[1];
            $replace = substr($fileData, 0, strpos($fileData, ',') + 1);
            $imageBytes = str_replace($replace, '', $fileData);
            $imageBytes = str_replace(' ', '+', $imageBytes);
            $fileName = time() . '_image.' . $extension;
            $filePath = 'chat_files/' . $fileName;
            Storage::disk('public')->put($filePath, base64_decode($imageBytes));
            $fileType = 'image';
        }

        $message = Message::create([
            'conversation_id' => $conversationId,
            'sender_id'       => auth()->id(),
            'message_text'    => $request->message_text,
            'file_path'       => $filePath,
            'file_type'       => $fileType,
            'is_read'         => false,
        ]);

        $message->load(['sender.profile']);

        // Update conversation updated_at so it bubbles to top in list
        $conversation->touch();

        // BROADCAST → private-conversation.{id}
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

        if ($updated > 0) {
            broadcast(new MessageRead($conversationId, $authId))->toOthers();
        }

        // 1. Fetch Messages
        $messages = Message::with('sender.profile')
            ->where('conversation_id', $conversationId)
            ->oldest()
            ->get()
            ->map(fn($m) => [
                'id'            => $m->id,
                'type'          => 'message',
                'sender_id'     => $m->sender_id,
                'sender_name'   => $m->sender->name,
                'sender_avatar' => optional($m->sender->profile)->avatar,
                'message_text'  => $m->message_text,
                'file_url'      => $m->file_url,
                'file_type'     => $m->file_type,
                'is_read'       => $m->is_read,
                'created_at'    => $m->created_at->toIso8601String(),
            ]);

        // 2. Fetch Pickups
        $pickups = Pickup::with(['requester:id,name', 'receiver:id,name'])
            ->where('conversation_id', $conversationId)
            ->get()
            ->map(fn($p) => [
                'id'             => $p->id,
                'type'           => 'pickup',
                'requester_id'   => $p->requester_id,
                'requester_name' => $p->requester->name ?? 'User',
                'receiver_id'    => $p->receiver_id,
                'receiver_name'  => $p->receiver->name ?? 'User',
                'status'         => $p->status,
                'pickup_date'    => $p->pickup_date,
                'pickup_time'    => $p->pickup_time,
                'location'       => $p->location,
                'notes'          => $p->notes,
                'created_at'     => $p->created_at->toIso8601String(),
            ]);

        // 3. Fetch Contact Shares
        $shares = ContactShare::with(['requester:id,name,email', 'receiver:id,name,email', 'receiver.profile'])
            ->where('conversation_id', $conversationId)
            ->get()
            ->map(function($s) {
                $contactData = null;
                if ($s->status === 'accepted') {
                   // Logic: Original receiver (who accepted) shared their info
                   $receiver = $s->receiver;
                   if ($receiver) {
                       $contactData = [
                           'user_name' => $receiver->name,
                           'email'     => $receiver->email,
                           'phone'     => optional($receiver->profile)->phone,
                       ];
                   }
                }
                
                return [
                    'id'             => $s->id,
                    'type'           => 'contact_share',
                    'requester_id'   => $s->requester_id,
                    'requester_name' => $s->requester->name ?? 'User',
                    'receiver_id'    => $s->receiver_id,
                    'receiver_name'  => $s->receiver->name ?? 'User',
                    'status'         => $s->status,
                    'contact'        => $contactData,
                    'created_at'     => $s->created_at->toIso8601String(),
                ];
            });

        // Combine and Sort by created_at
        $combined = $messages->concat($pickups)->concat($shares)->sortBy('created_at')->values();

        return response()->json(['status' => true, 'data' => $combined]);
    }
}
