<?php

namespace App\Http\Controllers\API\Sabbir;

use App\Events\ContactShareStatusChanged;
use App\Http\Controllers\Controller;
use App\Models\ContactShare;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactShareController extends Controller
{
    // ─────────────────────────────────────────────────────────────────
    // STEP 4 — POST /api/conversations/{id}/contact-share/request
    // ─────────────────────────────────────────────────────────────────
    public function request(int $conversationId): JsonResponse
    {
        $conversation = Conversation::findOrFail($conversationId);
        $authId       = auth()->id();

        if (!$conversation->hasUser($authId)) {
            return response()->json(['status' => false, 'message' => 'Unauthorized.'], 403);
        }

        if ($conversation->status !== 'accepted') {
            return response()->json(['status' => false, 'message' => 'Conversation must be accepted.'], 422);
        }

        $receiverId = $conversation->otherUserId($authId);

        $alreadyExists = ContactShare::where('conversation_id', $conversationId)
            ->where('requester_id', $authId)
            ->whereIn('status', ['pending', 'accepted'])
            ->exists();

        if ($alreadyExists) {
            return response()->json(['status' => false, 'message' => 'Request already sent.'], 409);
        }

        $share = ContactShare::create([
            'conversation_id' => $conversationId,
            'requester_id'    => $authId,
            'receiver_id'     => $receiverId,
            'status'          => 'pending',
        ]);

        // BROADCAST → private-conversation.{id}
        broadcast(new ContactShareStatusChanged($share))->toOthers();

        return response()->json(['status' => true, 'message' => 'Contact share request sent.']);
    }

    // ─────────────────────────────────────────────────────────────────
    // STEP 5 — POST /api/contact-shares/{id}/respond
    // ─────────────────────────────────────────────────────────────────
    public function respond(Request $request, int $id): JsonResponse
    {
        $data  = $request->validate(['status' => 'required|in:accepted,rejected']);
        $share = ContactShare::findOrFail($id);

        if ($share->receiver_id !== auth()->id()) {
            return response()->json(['status' => false, 'message' => 'Unauthorized.'], 403);
        }

        if ($share->status !== 'pending') {
            return response()->json(['status' => false, 'message' => 'Already responded.'], 422);
        }

        $share->update(['status' => $data['status']]);

        $contactData = null;

        if ($data['status'] === 'accepted') {
            $receiver = auth()->user()->load('profile');
            $contactData = [
                'user_name' => $receiver->name,
                'email'     => $receiver->email,
                'phone'     => $receiver->profile->phone ?? null,
            ];
        }

        // BROADCAST → private-conversation.{id}
        broadcast(new ContactShareStatusChanged($share->fresh(), $contactData))->toOthers();

        if ($data['status'] === 'rejected') {
            return response()->json(['status' => true, 'message' => 'Request rejected.']);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Contact shared successfully.',
            'data'    => $contactData,
        ]);
    }
}