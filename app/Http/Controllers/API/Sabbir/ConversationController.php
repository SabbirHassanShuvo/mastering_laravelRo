<?php

namespace App\Http\Controllers\API\Sabbir;

use App\Events\ConversationStatusChanged;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Matche;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    // ─────────────────────────────────────────────────────────────────
    // STEP 1 — POST /api/conversations/request
    // ─────────────────────────────────────────────────────────────────
    public function request(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id'  => 'required|exists:products,id',
            'receiver_id' => 'required|exists:users,id',
            'message'     => 'required|string|max:2000',
        ]);

        $authId     = auth()->id();
        $receiverId = (int) $data['receiver_id'];
        $productId  = (int) $data['product_id'];

        if ($authId === $receiverId) {
            return response()->json(['status' => false, 'message' => 'Cannot message yourself.'], 422);
        }

        // Verify match exists
        $matched = Matche::where('product_id', $productId)
            ->where(function ($q) use ($authId, $receiverId) {
                $q->where('user_one_id', $authId)->where('user_two_id', $receiverId);
            })
            ->orWhere(function ($q) use ($authId, $receiverId, $productId) {
                $q->where('product_id', $productId)
                    ->where('user_one_id', $receiverId)
                    ->where('user_two_id', $authId);
            })->exists();

        if (!$matched) {
            return response()->json(['status' => false, 'message' => 'Only matched users can start a conversation.'], 403);
        }

        // Check existing conversation
        $existing = Conversation::where('product_id', $productId)
            ->where(function ($q) use ($authId, $receiverId) {
                $q->where('user_one_id', $authId)->where('user_two_id', $receiverId);
            })
            ->orWhere(function ($q) use ($authId, $receiverId, $productId) {
                $q->where('product_id', $productId)
                    ->where('user_one_id', $receiverId)
                    ->where('user_two_id', $authId);
            })->first();

        if ($existing) {
            return response()->json([
                'status'  => false,
                'message' => 'Conversation already exists.',
                'data'    => ['conversation_id' => $existing->id],
            ], 409);
        }

        $conversation = Conversation::create([
            'product_id'  => $productId,
            'user_one_id' => $authId,
            'user_two_id' => $receiverId,
            'status'      => 'pending',
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $authId,
            'message_text'    => $data['message'],
        ]);

        // BROADCAST → private-user.{receiver_id}
        // Flutter receives this on the receiver's private channel
        broadcast(new ConversationStatusChanged($conversation))->toOthers();

        return response()->json([
            'status'  => true,
            'message' => 'Message request sent successfully.',
            'data'    => ['conversation_id' => $conversation->id],
        ], 201);
    }

    // ─────────────────────────────────────────────────────────────────
    // STEP 2 — POST /api/conversations/{id}/respond
    // ─────────────────────────────────────────────────────────────────
    public function respond(Request $request, int $id): JsonResponse
    {
        $data         = $request->validate(['status' => 'required|in:accepted,rejected']);
        $conversation = Conversation::findOrFail($id);

        if ($conversation->user_two_id !== auth()->id()) {
            return response()->json(['status' => false, 'message' => 'Unauthorized.'], 403);
        }

        if ($conversation->status !== 'pending') {
            return response()->json(['status' => false, 'message' => 'Already responded.'], 422);
        }

        $conversation->update(['status' => $data['status']]);

        // BROADCAST → private-user.{user_one_id} (the requester)
        broadcast(new ConversationStatusChanged($conversation->fresh()))->toOthers();

        $msg = $data['status'] === 'accepted'
            ? 'Conversation accepted. You can now chat.'
            : 'Conversation rejected.';

        return response()->json(['status' => true, 'message' => $msg]);
    }

    // ─────────────────────────────────────────────────────────────────
    // GET /api/conversations
    // ─────────────────────────────────────────────────────────────────
    public function index(Request $request): JsonResponse
    {
        $userId = auth()->id();
        $type = $request->query('type', 'all');

        $query = Conversation::with([
            'product:id,title,product_image,user_id',
            'userOne:id,name,avatar',
            'userTwo:id,name,avatar',
            'messages' => fn($q) => $q->latest()->limit(1),
        ])
            ->where(function ($q) use ($userId) {
                $q->where('user_one_id', $userId)
                  ->orWhere('user_two_id', $userId);
            });

        if ($type === 'selling') {
            $query->whereHas('product', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        } elseif ($type === 'buying') {
            $query->whereHas('product', function ($q) use ($userId) {
                $q->where('user_id', '!=', $userId);
            });
        }

        $conversations = $query->latest('updated_at')
            ->get()
            ->map(function ($c) use ($userId) {
                $other = $c->user_one_id === $userId ? $c->userTwo : $c->userOne;
                return [
                    'id'           => $c->id,
                    'product'      => $c->product,
                    'other_user'   => $other,
                    'status'       => $c->status,
                    'last_message' => $c->messages->first(),
                    'unread_count' => $c->messages()
                        ->where('sender_id', '!=', $userId)
                        ->where('is_read', false)
                        ->count(),
                    'updated_at'   => $c->updated_at,
                ];
            });

        return response()->json(['status' => true, 'data' => $conversations]);
    }

    // ─────────────────────────────────────────────────────────────────
    // GET /api/conversations/{id}
    // ─────────────────────────────────────────────────────────────────
    public function show(int $id): JsonResponse
    {
        $conversation = Conversation::with([
            'product:id,name,images',
            'userOne:id,name,avatar,phone,email',
            'userTwo:id,name,avatar,phone,email',
        ])->findOrFail($id);

        if (!$conversation->hasUser(auth()->id())) {
            return response()->json(['status' => false, 'message' => 'Unauthorized.'], 403);
        }

        return response()->json(['status' => true, 'data' => $conversation]);
    }
}