<?php

namespace App\Http\Controllers\API\Sabbir;

use App\Events\PickupStatusChanged;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Pickup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PickupController extends Controller
{
    // ─────────────────────────────────────────────────────────────────
    // STEP 6 — POST /api/conversations/{id}/pickups/schedule
    // ─────────────────────────────────────────────────────────────────
    public function schedule(Request $request, int $conversationId): JsonResponse
    {
        $data = $request->validate([
            'pickup_date' => 'required|date|after_or_equal:today',
            'pickup_time' => 'required|date_format:H:i',
            'location'    => 'required|string|max:255',
            'notes'       => 'nullable|string|max:1000',
        ]);

        $conversation = Conversation::findOrFail($conversationId);
        $authId       = auth()->id();

        if (!$conversation->hasUser($authId)) {
            return response()->json(['status' => false, 'message' => 'Unauthorized.'], 403);
        }

        if ($conversation->status !== 'accepted') {
            return response()->json(['status' => false, 'message' => 'Conversation must be accepted to schedule a pickup.'], 422);
        }

        $pickup = Pickup::create([
            'conversation_id' => $conversationId,
            'product_id'      => $conversation->product_id,
            'requester_id'    => $authId,
            'receiver_id'     => $conversation->otherUserId($authId),
            'pickup_date'     => $data['pickup_date'],
            'pickup_time'     => $data['pickup_time'],
            'location'        => $data['location'],
            'notes'           => $data['notes'] ?? null,
            'status'          => 'pending',
        ]);

        $pickup->load(['product:id,title,product_image', 'requester:id,name', 'receiver:id,name']);

        // BROADCAST → private-conversation.{id}
        broadcast(new PickupStatusChanged($pickup))->toOthers();

        return response()->json([
            'status'  => true,
            'message' => 'Pickup proposal sent.',
            'data'    => $pickup,
        ], 201);
    }

    // ─────────────────────────────────────────────────────────────────
    // STEP 7 — POST /api/pickups/{id}/respond
    // ─────────────────────────────────────────────────────────────────
    public function respond(Request $request, int $id): JsonResponse
    {
        $data   = $request->validate(['status' => 'required|in:accepted,rejected']);
        $pickup = Pickup::findOrFail($id);

        if ($pickup->receiver_id !== auth()->id()) {
            return response()->json(['status' => false, 'message' => 'Unauthorized.'], 403);
        }

        if ($pickup->status !== 'pending') {
            return response()->json(['status' => false, 'message' => 'Already responded.'], 422);
        }

        $pickup->update(['status' => $data['status']]);

        // BROADCAST → private-conversation.{id}
        broadcast(new PickupStatusChanged($pickup->fresh()))->toOthers();

        $msg = $data['status'] === 'accepted'
            ? 'Pickup accepted.'
            : 'Pickup rejected.';

        return response()->json(['status' => true, 'message' => $msg]);
    }

    // ─────────────────────────────────────────────────────────────────
    // STEP 8 — POST /api/pickups/{id}/confirm
    // ─────────────────────────────────────────────────────────────────
    public function confirm(Request $request, int $id): JsonResponse
    {
        $data   = $request->validate(['status' => 'required|in:completed,failed']);
        $pickup = Pickup::with('conversation.product')->findOrFail($id);
        $authId = auth()->id();

        if (!$pickup->hasUser($authId)) {
            return response()->json(['status' => false, 'message' => 'Unauthorized.'], 403);
        }

        if ($pickup->status !== 'accepted') {
            return response()->json(['status' => false, 'message' => 'Only accepted pickups can be confirmed.'], 422);
        }

        $pickup->update(['status' => $data['status']]);

        if ($data['status'] === 'completed') {
            $pickup->product()->update([
                'status'  => 'sold',
                'sold_at' => now(),
            ]);

            // Automatic Verification Check
            $pickup->requester->checkVerifyStatus();
            $pickup->receiver->checkVerifyStatus();
        }

        // BROADCAST → private-conversation.{id}
        broadcast(new PickupStatusChanged($pickup->fresh()))->toOthers();

        $msg = $data['status'] === 'completed'
            ? 'Pickup completed. You can now leave a review.'
            : 'Pickup marked as failed.';

        return response()->json(['status' => true, 'message' => $msg]);
    }

    // ─────────────────────────────────────────────────────────────────
    // GET /api/conversations/{id}/pickups
    // List all pickups for a conversation
    // ─────────────────────────────────────────────────────────────────
    public function index(int $conversationId): JsonResponse
    {
        $conversation = Conversation::findOrFail($conversationId);
        $authId       = auth()->id();

        if (!$conversation->hasUser($authId)) {
            return response()->json(['status' => false, 'message' => 'Unauthorized.'], 403);
        }

        $pickups = Pickup::with(['product:id,title,product_image', 'requester:id,name', 'receiver:id,name'])
            ->where('conversation_id', $conversationId)
            ->latest()
            ->get()
            ->map(fn($p) => [
                'id'              => $p->id,
                'conversation_id' => $p->conversation_id,
                'product_id'      => $p->product_id,
                'product_title'   => $p->product->title ?? 'Deleted Product',
                'product_image'   => $p->product->product_image ?? null,
                'requester_id'    => $p->requester_id,
                'requester_name'  => $p->requester->name ?? 'User',
                'receiver_id'     => $p->receiver_id,
                'receiver_name'   => $p->receiver->name ?? 'User',
                'pickup_date'     => $p->pickup_date,
                'pickup_time'     => $p->pickup_time,
                'location'        => $p->location,
                'notes'           => $p->notes,
                'status'          => $p->status,
                'is_requester'    => $p->requester_id === $authId,
                'created_at'      => $p->created_at->toISOString(),
                'updated_at'      => $p->updated_at->toISOString(),
            ]);

        return response()->json(['status' => true, 'data' => $pickups]);
    }
}