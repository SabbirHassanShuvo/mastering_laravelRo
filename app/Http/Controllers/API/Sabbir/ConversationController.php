<?php

namespace App\Http\Controllers\API\Sabbir;

use App\Events\ConversationStatusChanged;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Matche;
use App\Models\Message;
use App\Notifications\ConversationAcceptedNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    // ─────────────────────────────────────────────────────────────────
    // STEP 1 — POST /api/conversations/request
    // ─────────────────────────────────────────────────────────────────
    // public function request(Request $request): JsonResponse
    // {
    //     $data = $request->validate([
    //         'product_id'  => 'required|exists:products,id',
    //         'receiver_id' => 'required|exists:users,id',
    //         'message'     => 'required|string|max:2000',
    //     ]);

    //     $authId     = auth()->id();
    //     $receiverId = (int) $data['receiver_id'];
    //     $productId  = (int) $data['product_id'];

    //     if ($authId === $receiverId) {
    //         return response()->json(['status' => false, 'message' => 'Cannot message yourself.'], 422);
    //     }

    //     // Verify match exists
    //     $matched = Matche::where('product_id', $productId)
    //         ->where(function ($q) use ($authId, $receiverId) {
    //             $q->where('user_one_id', $authId)->where('user_two_id', $receiverId);
    //         })
    //         ->orWhere(function ($q) use ($authId, $receiverId, $productId) {
    //             $q->where('product_id', $productId)
    //                 ->where('user_one_id', $receiverId)
    //                 ->where('user_two_id', $authId);
    //         })->exists();

    //     if (!$matched) {
    //         return response()->json(['status' => false, 'message' => 'Only matched users can start a conversation.'], 403);
    //     }

    //     // Check existing conversation
    //     $existing = Conversation::where('product_id', $productId)
    //         ->where(function ($q) use ($authId, $receiverId) {
    //             $q->where('user_one_id', $authId)->where('user_two_id', $receiverId);
    //         })
    //         ->orWhere(function ($q) use ($authId, $receiverId, $productId) {
    //             $q->where('product_id', $productId)
    //                 ->where('user_one_id', $receiverId)
    //                 ->where('user_two_id', $authId);
    //         })->first();

    //     if ($existing) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'Conversation already exists.',
    //             'data'    => ['conversation_id' => $existing->id],
    //         ], 409);
    //     }

    //     $conversation = Conversation::create([
    //         'product_id'  => $productId,
    //         'user_one_id' => $authId,
    //         'user_two_id' => $receiverId,
    //         'status'      => 'pending',
    //     ]);

    //     Message::create([
    //         'conversation_id' => $conversation->id,
    //         'sender_id'       => $authId,
    //         'message_text'    => $data['message'],
    //     ]);

    //     // BROADCAST → private-user.{receiver_id}
    //     // Flutter receives this on the receiver's private channel
    //     broadcast(new ConversationStatusChanged($conversation))->toOthers();

    //     return response()->json([
    //         'status'  => true,
    //         'message' => 'Message request sent successfully.',
    //         'data'    => ['conversation_id' => $conversation->id],
    //     ], 201);
    // }

    // ─────────────────────────────────────────────────────────────────
    // STEP 2 — POST /api/conversations/{id}/respond
    // ─────────────────────────────────────────────────────────────────
    public function respond(Request $request, int $id): JsonResponse
    {
        $data         = $request->validate(['status' => 'required|in:accepted,rejected']);
        $conversation = Conversation::findOrFail($id);

        if ($conversation->user_two_id !== auth()->id()) {
            return response()->json([
                'status'  => false,
                'message' => 'You are not allowed to respond to this conversation.',
            ], 403);
        }

        if ($conversation->status !== 'pending') {
            return response()->json([
                'status'  => false,
                'message' => 'You have already responded to this request.',
            ], 422);
        }

        $productTitle = $conversation->product->title ?? 'this product';
        $buyerName    = $conversation->userOne->name  ?? 'The user';

        // ── Rejected ───────────────────────────────────────────────────
        if ($data['status'] === 'rejected') {
            broadcast(new ConversationStatusChanged($conversation))->toOthers();

            $conversation->messages()->delete();
            $conversation->delete();

            return response()->json([
                'status'  => true,
                'message' => "You have declined {$buyerName}'s interest in \"{$productTitle}\".",
            ]);
        }

        // ── Accepted ───────────────────────────────────────────────────
        $conversation->update(['status' => 'accepted']);

        $conversation->userOne->notify(new ConversationAcceptedNotification($conversation));

        broadcast(new ConversationStatusChanged($conversation->fresh()))->toOthers();

        return response()->json([
            'status'  => true,
            'message' => "You accepted {$buyerName}'s interest in \"{$productTitle}\". You can now start chatting!",
        ]);
    }

    // ─────────────────────────────────────────────────────────────────
    // GET /api/conversations
    // ─────────────────────────────────────────────────────────────────
    public function index(Request $request): JsonResponse
    {
        $userId = auth()->id();
        $type = $request->query('type', 'all');

        $query = Conversation::with([
            'product:id,title,product_image,user_id,price',
            'userOne:id,name',
            'userOne.profile:id,user_id,avatar',
            'userTwo:id,name',
            'userTwo.profile:id,user_id,avatar',
            'messages' => fn($q) => $q->latest()->limit(1),
        ])
            ->where(function ($query) use ($userId) {
                // Wrap visibility logic in a group
                $query->where(function ($q) use ($userId) {
                    // Case 1: Status is accepted -> both can see
                    $q->where('status', 'accepted')
                      ->where(function ($sq) use ($userId) {
                          $sq->where('user_one_id', $userId)
                             ->orWhere('user_two_id', $userId);
                      });
                })
                ->orWhere(function ($q) use ($userId) {
                    // Case 2: Status is pending -> only receiver (user_two) can see
                    $q->where('status', 'pending')
                      ->where('user_two_id', $userId);
                });
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
                // Determine who the "other" user is
                $other = $c->user_one_id === $userId ? $c->userTwo : $c->userOne;
                $otherAvatar = $other->profile->avatar ?? null;
                
                // Logic for "New Interest" (Interest Request)
                // - Status is pending
                // - Current user is user_two (the receiver/product owner)
                $isInterestRequest = ($c->status === 'pending' && $c->user_two_id === $userId);
                
                $lastMsg = $c->messages->first();
                
                return [
                    'id'              => $c->id,
                    'status'          => $c->status,
                    'product'         => $c->product,
                    'other_user'      => [
                        'id'     => $other->id,
                        'name'   => $other->name,
                        'avatar' => $otherAvatar,
                    ],
                    'display_type'    => $isInterestRequest ? 'interest_request' : 'message',
                    'display_title'   => $isInterestRequest ? 'New Interest' : ($other->name ?? 'User'),
                    'display_message' => $isInterestRequest 
                        ? ($other->name ?? 'Someone') . ' sent a interest request.' 
                        : ($lastMsg->message_text ?? ''),
                    'matched_on'      => $c->product->title ?? 'Product',
                    'product_image'   => $c->product->product_image ?? null,
                    'product_price'   => $c->product->price ?? null,
                    'last_message'    => $lastMsg,
                    'unread_count'    => $c->messages()
                        ->where('sender_id', '!=', $userId)
                        ->where('is_read', false)
                        ->count(),
                    'updated_at'      => $c->updated_at,
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
            'product:id,title,product_image,user_id,price',
            'userOne:id,name,email',
            'userOne.profile:id,user_id,avatar',
            'userTwo:id,name,email',
            'userTwo.profile:id,user_id,avatar',
        ])->findOrFail($id);

        if (!$conversation->hasUser(auth()->id())) {
            return response()->json(['status' => false, 'message' => 'Unauthorized.'], 403);
        }

        return response()->json(['status' => true, 'data' => $conversation]);
    }
}